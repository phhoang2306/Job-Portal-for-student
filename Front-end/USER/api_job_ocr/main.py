import os
import urllib.parse
from dotenv import load_dotenv
from paginate_sqlalchemy import SqlalchemyOrmPage
import math
import pandas as pd
import mysql.connector
from elasticsearch import Elasticsearch
from pymongo import MongoClient
from fastapi import FastAPI, Query
from fastapi.responses import JSONResponse
from fastapi import HTTPException, status
from requests.exceptions import ConnectionError, Timeout
import numpy as np
import warnings
import re
import json
import requests
from fastapi.middleware.cors import CORSMiddleware
import urllib.parse
import nltk
import spacy
import string
from pydantic import BaseModel
from typing import List
from nltk.stem import WordNetLemmatizer
from nltk import word_tokenize
import random
import datetime
from bson.regex import Regex
import warnings; warnings.simplefilter('ignore')
from tika import parser
import cv2
import re
import shutil
import pyrebase
from pdf2image import convert_from_path
import dlib
from fastapi import FastAPI, UploadFile, File


# Load environment variables from .env file
load_dotenv()

app = FastAPI()

# Enable CORS
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # Replace with the appropriate origins if needed
    allow_methods=["*"],  # Or specify the allowed HTTP methods
    allow_headers=["*"],  # Or specify the allowed headers
)

# Define Elasticsearch connection
es = Elasticsearch([os.environ.get("ELASTICSEARCH_HOST")])

# Connect to MongoDB
client = MongoClient(os.environ.get("MONGODB_CONNECTION_STRING"))
db = client['BaseOnAL']
collection = db['test']

# Establish MySQL connection
cnx = mysql.connector.connect(
    user=os.environ.get("MYSQL_USER"),
    database=os.environ.get("MYSQL_DATABASE"),
    password=os.environ.get("MYSQL_PASSWORD")
)
cursor = cnx.cursor()

index_name = "jobs_index"

# Đường dẫn đến tệp trên Google Drive
file_stpwd = os.environ.get("GOOGLE_DRIVE_FILE_URL")

# Tên biến toàn cục để lưu trữ nội dung của tệp
stopwords_vn = None

def load_stopwords():
    global stopwords_vn

    if stopwords_vn is None:
        # Tải tệp từ URL nếu chưa được tải
        response = requests.get(file_stpwd)
        stopwords_vn = response.text.splitlines()

    return stopwords_vn

# Gọi hàm load_stopwords() để đảm bảo tệp đã được tải trước khi sử dụng
stop_words = load_stopwords()

file_url = os.environ.get("JOB_FILE_URL")

df = pd.read_csv(file_url)

# Delete all data in Elasticsearch
def delete_all_data():
    response = es.indices.delete(index="_all")
    return response

delete_all_data()

# Create or update Elasticsearch index
index_mapping = {
    "settings": {
        "analysis": {
            "filter": {
                "vietnamese_stop": {
                    "type": "stop",
                    "stopwords": stop_words
                }
            },
            "analyzer": {
                "vietnamese_analyzer": {
                    "tokenizer": "standard",
                    "filter": [
                        "lowercase",
                        "vietnamese_stop"
                    ]
                }
            }
        }
    },
    "mappings": {
        "properties": {
            "job_id": {"type": "integer"},
            "Số lượng tuyển": {"type": "integer"},
            "Hình thức làm việc": {"type": "text"},
            "Cấp bậc": {"type": "text"},
            "Giới tính": {"type": "text"},
            "min_yoe": {"type": "integer"},
            "max_yoe": {"type": "integer"},
            "Mô tả công việc": {"type": "text"},
            "Yêu cầu ứng viên": {"type": "text"},
            "min_salary": {"type": "integer"},
            "max_salary": {"type": "integer"},         
        }
    }
}

if not es.indices.exists(index=index_name):
    es.indices.create(index=index_name, body=index_mapping)
else:
    es.indices.put_mapping(index=index_name, body=index_mapping['mappings'])

# Index job data into Elasticsearch
for _, row in df.iterrows():
    job_data = row.to_dict()
    # Convert skills to a list
    job_data['skills'] = [skill.strip() for skill in job_data['skills'].split(",")]
    es.index(index=index_name, body=job_data)

@app.get("/jobs")
def search_jobs(
    keyword: str = Query(None, description="Keyword to search for"),
    addresses: str = Query(None, description="addresses filter"),
    skill: str = Query(None, description="Skill filter"),
    categories: str = Query(None, description="Categories filter"),
    page: int = Query(1, description="Page number"),
    limit: int = Query(10, description="Number of results per page"),
):
    try:
        # Search for jobs in Elasticsearch
        body = {
            "size": limit,
            "from": (page - 1) * limit,
            "query": {
                "bool": {
                    "must": [
                        {"multi_match": {"query": keyword, "fields": ["*"]}}
                    ],
                    "filter": []
                }
            }
        }

        if addresses:
            body["query"]["bool"]["filter"].append({"match": {"addresses": addresses}})

        if skill:
            body["query"]["bool"]["filter"].append({"match": {"skills": skill}})

        if categories:
            decoded_categories = urllib.parse.unquote(categories)
            body["query"]["bool"]["filter"].append({"match": {"categories": decoded_categories}})

        result = es.search(index="jobs_index", body=body)
        hits = result["hits"]["hits"]

        max_score = result['hits']['max_score']
        min_score = max_score * 0.7
        print(min_score)

        jobs = []
        for hit in hits:
            job_info = hit["_source"]
            job_info["score"] = hit["_score"]
            jobs.append(job_info)

        filtered_jobs = [job for job in jobs if job["score"] > min_score]

        total = len(filtered_jobs)

        # Calculate pagination information
        total_pages = math.ceil(total / limit)
        base_url = f"http://localhost:8000/jobs?keyword={keyword}&addresses={addresses}&skill={skill}&categories={categories}"
        first_page_url = f"{base_url}&page=1"
        last_page = total_pages
        last_page_url = f"{base_url}&page={last_page}"
        next_page = page + 1 if page < total_pages else None
        prev_page = page - 1 if page > 1 else None

        links = [
            {
                "url": None,
                "label": "&laquo; Previous",
                "active": False
            },
            {
                "url": first_page_url,
                "label": "1",
                "active": page == 1
            }
        ]

        for i in range(2, total_pages + 1):
            links.append({
                "url": f"{base_url}&page={i}",
                "label": str(i),
                "active": page == i
            })

        links.append({
            "url": f"{base_url}&page={next_page}" if next_page else None,
            "label": "Next &raquo;",
            "active": False
        })

        if len(filtered_jobs) == 0:
            return {
                "error": False,
                "message": "Không tìm thấy công việc",
                "data": None,
                "status_code": 400
            }
        
        pagination_info = {
            "first_page_url": first_page_url,
            "from": (page - 1) * limit + 1,
            "last_page": last_page,
            "last_page_url": last_page_url,
            "links": links,
            "next_page_url": f"{base_url}&page={next_page}" if next_page else None,
            "path": f"http://localhost:8000/jobs?keyword={keyword}&addresses={addresses}&skill={skill}&categories={categories}",
            "per_page": limit,
            "prev_page_url": f"{base_url}&page={prev_page}" if prev_page else None,
            "to": min(page * limit, total),
            "total": total
        }

        return {
            "error": False,
            "message": "Xử lí thành công",
            "data": {
                "jobs": {
                    "current_page": page,
                    "data": filtered_jobs,
                    "pagination_info": pagination_info
                }
            },
            "status_code": status.HTTP_200_OK
        }

    except (ConnectionError, TimeoutError, Timeout) as e:
        # Xử lý lỗi mạng
        return {
            "error": True,
            "message": "Lỗi mạng",
            "data": [],
            "status_code": HTTP_503_SERVICE_UNAVAILABLE
        }
    except (ValueError, TypeError) as e:
        # Xử lý lỗi đầu vào gây crash hoặc lỗi xử lý không mong muốn
        return {
            "error": True,
            "message": "Lỗi đầu vào gây crash",
            "data": [],
            "status_code": status.HTTP_400_BAD_REQUEST
        }
    except Exception as e:
        return {
            "error": True,
            "message": "Lổi đầu vào không hợp lệ/ Lỗi website đang gặp sự cố",
            "data": [],
            "status_code": status.HTTP_500_INTERNAL_SERVER_ERROR
        }

def find_user_ids_by_job_title(job_title: str = Query(...)):
    pipeline = [
        {"$match": {"jobs.title": job_title}},
        {"$project": {"_id": 0, "user_id": "$user_id"}}
    ]
    
    result = collection.aggregate(pipeline)
    user_ids = [doc["user_id"] for doc in result]
    
    return user_ids

@app.get("/user_profiles/")
def get_user_profiles(job_title: str = Query(...), page: int = 1, limit: int = 10):
    try:
        user_ids = find_user_ids_by_job_title(job_title)
        
        if not user_ids:
            return {
                "error": False,
                "message": "Không có sinh viên được gợi ý job này",
                "data": {
                    "user_profiles": {
                        "current_page": page,
                        "data": [],
                        "pagination_info": {
                            "first_page_url": None,
                            "from": 0,
                            "last_page": 0,
                            "last_page_url": None,
                            "links": [],
                            "next_page_url": None,
                            "path": f"http://localhost:8001/user_profiles/?job_title={job_title}",
                            "per_page": limit,
                            "prev_page_url": None,
                            "to": 0,
                            "total": 0
                        }
                    }
                },
                "status_code": 404
            }

        random.shuffle(user_ids)  # Shuffle the user_ids

        # Get 10 random user_ids if there are more than 10
        if len(user_ids) > 10:
            user_ids = user_ids[:10]

        # Query to fetch user profile information from MySQL
        query = f"""
            SELECT
                up.id,
                up.full_name,
                up.avatar,
                up.about_me,
                up.good_at_position,
                up.year_of_experience,
                up.date_of_birth,
                up.gender,
                up.address,
                up.email,
                up.phone,
                GROUP_CONCAT(DISTINCT us.skill) AS skills,
                GROUP_CONCAT(DISTINCT ue.description ORDER BY ue.user_id SEPARATOR '; ') AS experiences,
                GROUP_CONCAT(DISTINCT ue.title ORDER BY ue.user_id SEPARATOR '; ') AS experiences_title
            FROM
                user_profiles up
            LEFT JOIN user_skills us ON up.id = us.user_id
            LEFT JOIN (
                SELECT
                    user_id,
                    GROUP_CONCAT(description ORDER BY user_id SEPARATOR '; ') AS description,
                    GROUP_CONCAT(title ORDER BY user_id SEPARATOR '; ') AS title
                FROM
                    user_experiences
                GROUP BY
                    user_id
            ) ue ON up.id = ue.user_id
            WHERE up.id IN ({', '.join(str(id) for id in user_ids)})
            GROUP BY
                up.id,
                up.full_name,
                up.avatar,
                up.about_me,
                up.good_at_position,
                up.year_of_experience,
                up.date_of_birth,
                up.gender,
                up.address,
                up.email,
                up.phone
            LIMIT {limit} OFFSET {(page - 1) * limit}
        """

        # Execute the query and fetch results from MySQL
        cursor = cnx.cursor()
        cursor.execute(query)
        results = cursor.fetchall()

        columns = [column[0] for column in cursor.description]
        df = pd.DataFrame(results, columns=columns)

        user_profiles = df.to_dict('records')
        random.shuffle(user_profiles)

        total = len(user_profiles)
        total_pages = math.ceil(total / limit)
        base_url = f"http://localhost:8001/user_profiles/?job_title={job_title}"
        first_page_url = f"{base_url}&page=1&limit={limit}"
        last_page = total_pages
        last_page_url = f"{base_url}&page={last_page}&limit={limit}"
        next_page = page + 1 if page < total_pages else None
        prev_page = page - 1 if page > 1 else None

        links = [
            {
                "url": None,
                "label": "&laquo; Previous",
                "active": False
            },
            {
                "url": first_page_url,
                "label": "1",
                "active": page == 1
            }
        ]

        for i in range(2, total_pages + 1):
            links.append({
                "url": f"{base_url}&page={i}&limit={limit}",
                "label": str(i),
                "active": page == i
            })

        links.append({
            "url": f"{base_url}&page={next_page}&limit={limit}" if next_page else None,
            "label": "Next &raquo;",
            "active": False
        })

        if len(user_profiles) == 0:
            return {
                "error": False,
                "message": "Không tìm thấy thông tin người dùng",
                "data": None,
                "status_code": 404
            }
        
        pagination_info = {
            "first_page_url": first_page_url,
            "from": (page - 1) * limit + 1,
            "last_page": last_page,
            "last_page_url": last_page_url,
            "links": links,
            "next_page_url": f"{base_url}&page={next_page}&limit={limit}" if next_page else None,
            "path": f"http://localhost:8001/user_profiles/?job_title={job_title}",
            "per_page": limit,
            "prev_page_url": f"{base_url}&page={prev_page}&limit={limit}" if prev_page else None,
            "to": min(page * limit, total),
            "total": total
        }

        return {
            "error": False,
            "message": "Xử lí thành công",
            "data": {
                "user_profiles": {
                    "current_page": page,
                    "data": user_profiles,
                    "pagination_info": pagination_info
                }
            },
            "status_code": 200
        }
    
    except (ConnectionError, TimeoutError, Timeout) as e:
        # Xử lý lỗi mạng
        return {
            "error": True,
            "message": "Lỗi mạng",
            "data": [],
            "status_code": HTTP_503_SERVICE_UNAVAILABLE
        }
    except (ValueError, TypeError) as e:
        # Xử lý lỗi đầu vào gây crash hoặc lỗi xử lý không mong muốn
        return {
            "error": True,
            "message": "Lỗi đầu vào gây crash",
            "data": [],
            "status_code": status.HTTP_400_BAD_REQUEST
        }
    except Exception as e:
        return {
            "error": True,
            "message": "Lổi đầu vào không hợp lệ/ Lỗi website đang gặp sự cố",
            "data": [],
            "status_code": status.HTTP_500_INTERNAL_SERVER_ERROR
        }

# recommned job

nltk.download('punkt', quiet=True, force=True)
nltk.download('wordnet', quiet=True, force=True)
nltk.download('averaged_perceptron_tagger', quiet=True, force=True)
nltk.download('omw-1.4', quiet=True, force=True)

# Tạo biến global để lưu trữ dữ liệu
timetable = None
users = None
jobs = None
user_acc = None

file_url = os.environ.get("JOB_FILE_URL")

jobs = pd.read_csv(file_url)

query_user = """
SELECT
  up.id,
  up.full_name,
  up.about_me,
  up.good_at_position,
  up.gender,
  up.address,
  up.year_of_experience,
  GROUP_CONCAT(DISTINCT us.skill) AS skills,
  GROUP_CONCAT(DISTINCT ue.description ORDER BY ue.user_id SEPARATOR ' ; ') AS experiences,
  GROUP_CONCAT(DISTINCT ue.title ORDER BY ue.user_id SEPARATOR ' ; ') AS experiences_title,
  GROUP_CONCAT(DISTINCT ua.description ORDER BY ua.user_id SEPARATOR ' ; ') AS achievements,
  up.created_at,
  up.updated_at
FROM
  user_profiles up
LEFT JOIN user_educations ued ON up.id = ued.user_id
LEFT JOIN user_achievements ua ON up.id = ua.user_id
LEFT JOIN user_skills us ON up.id = us.user_id
LEFT JOIN (
  SELECT
    user_id,
    GROUP_CONCAT(description ORDER BY user_id SEPARATOR '; ') AS description,
    GROUP_CONCAT(title ORDER BY user_id SEPARATOR '; ') AS title
  FROM
    user_experiences
  GROUP BY
    user_id
) ue ON up.id = ue.user_id
GROUP BY
  up.id, up.full_name, up.about_me, up.good_at_position, up.gender;
"""

cursor.execute(query_user)
user_results = cursor.fetchall()

# Get the column names for users
user_columns = [desc[0] for desc in cursor.description]

# Create a DataFrame for users
users = pd.DataFrame(user_results, columns=user_columns)

users['experiences'].fillna('Không có', inplace=True)
users['experiences_title'].fillna('Không có', inplace=True)
users['achievements'].fillna('Không có', inplace=True)
users['year_of_experience'].fillna('0', inplace=True)
users['year_of_experience'] = users['year_of_experience'].astype(np.int64)

# Define user_account
user_account = """
    select
      id,
      username,
      password,
      is_banned,
      locked_until,
      last_login
    from 
      user_accounts
    """
# Execute the timetable query and fetch the results
cursor.execute(user_account)
acc_results = cursor.fetchall()

# Get the column names for timetable
acc_columns = [desc[0] for desc in cursor.description]

# Create a DataFrame for timetable
user_acc = pd.DataFrame(acc_results, columns=acc_columns)

# Define timetable
time_table = """
    select
      id,
      user_id,
      coordinate
    from 
      time_tables
    """

# Execute the timetable query and fetch the results
cursor.execute(time_table)
timetable_results = cursor.fetchall()

# Get the column names for timetable
timetable_columns = [desc[0] for desc in cursor.description]

# Create a DataFrame for timetable
timetable = pd.DataFrame(timetable_results, columns=timetable_columns)
timetable['coordinate'] = timetable['coordinate'].replace('', '0,0')

stop = stopwords_vn
stop_words_ = set(stopwords_vn)
wn = WordNetLemmatizer()
vietnamese_lower = "aáàảãạăắằẳẵặâấầẩẫậbcedđeéèẻẽẹêếềểễệghiíìỉĩịjklmnoóòỏõọôốồổỗộơớờởỡợpqrstuúùủũụưứừửữựvxyýỳỷỹỵ"

def black_txt(token):
    return  token not in stop_words_ and token not in list(string.punctuation)  and len(token)>2

def clean_txt(text):
    clean_text = []
    clean_text2 = []
    text = re.sub("'", "", text)
    text = re.sub("(\\d|\\W)+", " ", text)
    text = re.sub(r'(?<=[a-zÀ-ỸẠ-Ỵ])(?=[A-ZĂÂBCDĐEÊGHIKLMNOÔƠPQRSTUƯVXY])', ' ', text)
    clean_text = [wn.lemmatize(word, pos="v") for word in word_tokenize(text.lower()) if black_txt(word)]
    clean_text2 = [word.replace('_', ' ') for word in clean_text if word not in vietnamese_lower]
    return " ".join(clean_text2)

weights = {
'title': 0.4,
'Yêu cầu ứng viên': 0.3,
'Mô tả công việc': 0.2,
'skills': 0.1
}
  
def calculate_sim_with_spacy(nlp, df, user_text, n=10):
    list_sim = []
    doc1 = list(nlp.pipe([user_text]))[0]  # Process the user_text using nlp.pipe()
    docs2 = list(nlp.pipe(df['combine']))  # Process the combine column using nlp.pipe()
    for i, doc2 in enumerate(docs2):
        try:
            # Calculate weighted similarity score
            score = 0
            for col, weight in weights.items():
                if col in df.columns:
                    doc1_col = doc1 if col == 'title' else doc1
                    doc2_col = doc2 if col == 'title' else doc2
                    col_score = doc1_col.similarity(doc2_col) * weight
                    score += col_score

            list_sim.append((doc1, doc2, i, score))
        except:
            continue

    return list_sim

def recommend_job(id: int, categories: str = None):
    # Kiểm tra xem ID người dùng có tồn tại trong DataFrame users hay không
    if id not in users['id'].values:
        return "User không tồn tại. Vui lòng kiểm tra lại."
    
    # Lấy tập hợp các ID từ user_acc
    user_acc_ids = set(user_acc['id'].values)

    # Lấy tập hợp các ID từ users
    users_ids = set(users['id'].values)

    # Tìm các ID có trong user_acc nhưng không có trong users
    missing_ids = user_acc_ids - users_ids

    # Kiểm tra xem id có trong user_acc nhưng không có trong users
    if id in missing_ids:
        return "Cần cập nhật đầy đủ thông tin trước khi chạy model"    

    for index, row in users.iterrows():
        if row['id'] == id:
            missing_columns = []

            if not row['full_name']:
                missing_columns.append('full_name')
            if not row['about_me']:
                missing_columns.append('about_me')
            if not row['good_at_position']:
                missing_columns.append('good_at_position')
            if not row['gender']:
                missing_columns.append('gender')
            if not row['address']:
                missing_columns.append('address')
            if row['year_of_experience'] is None:
                missing_columns.append('year_of_experience')
            if not row['skills']:
                missing_columns.append('skills')
            if not row['experiences']:
                missing_columns.append('experiences')
            if not row['experiences_title']:
                missing_columns.append('experiences_title')
            if not row['achievements']:
                missing_columns.append('achievements')

            if missing_columns:
                return "Cần cập nhật đầy đủ thông tin trước khi chạy model."
    
    # Tạo ma trận 24x7
    matrix = np.zeros((24, 7), dtype=int)

    # Chọn user_id cần xem
    user_id = id

    # Lấy thông tin lịch trình của user_id
    user_timetable = timetable[timetable['user_id'] == user_id]['coordinate'].values[0]

    # Kiểm tra nếu user_timetable không phải NaN
    if not pd.isnull(user_timetable):
        # Chia các tọa độ thành danh sách con
        coordinate_list = user_timetable.split(";")

        # Kiểm tra mỗi tọa độ trong danh sách con
        for coordinate in coordinate_list:
            day, hour = map(int, coordinate.split(","))
            matrix[hour - 1][day - 1] = 1

    # Xét từng cột và đếm số lượng số 1 trong mỗi cột
    ones_count = np.sum(matrix[6:19, :], axis=0)

    # Tìm các cột có số lượng số 1 xuất hiện nhiều hơn hoặc bằng 8 lần
    busy_columns = np.where(ones_count >= 8)[0]

    if len(busy_columns) >= 2:
        t = "Bán thời gian|Không yêu cầu"
    else:
        t = "Thực tập|Toàn thời gian|Không yêu cầu"

     # Xử lý timetable
    jobs_t = jobs[jobs['type'].str.contains(f'{t}', case=False)]

    # Lọc các công việc dựa trên giới tính
    gender = users[users['id'] == user_id]['gender'].iloc[0]
    
    if gender == 'Nữ':
        jobs_g = jobs_t[(jobs_t['gender'] == 'Nữ') | (jobs_t['gender'] == 'Không yêu cầu')]
    elif gender == 'Nam':
        jobs_g = jobs_t[(jobs_t['gender'] == 'Nam') | (jobs_t['gender'] == 'Không yêu cầu')]
    else:
        jobs_g = jobs_t


    # Lọc các công việc dựa trên địa chỉ
    user_address = users.loc[users['id'] == user_id, 'address'].iloc[0]
    user_address = user_address.strip()

    jobs_a = None

    if user_address in ['TPHCM', 'Hồ Chí Minh']:
        jobs_a = jobs_g[jobs_g[['addresses', 'title']].apply(lambda x: any(keyword in x['addresses'] or keyword in x['title'] for keyword in ['TPHCM', 'Hồ Chí Minh']), axis=1)]
    elif user_address in ['Hà Nội', 'HN']:
        jobs_a = jobs_g[jobs_g[['addresses', 'title']].apply(lambda x: any(keyword in x['addresses'] or keyword in x['title'] for keyword in ['Hà Nội', 'HN']), axis=1)]

    if jobs_a is None:
        jobs_a = jobs_g[jobs_g[['addresses', 'title']].apply(lambda x: user_address in x['addresses'] or user_address in x['title'], axis=1)]

    if jobs_a.empty:
        jobs_a = jobs_g


    # Lấy YearsExperience của người dùng   
    user_experience = users[users['id'] == user_id]['year_of_experience'].values[0]
    if user_experience == 0:
        min_yoe_condition = [0]
        max_yoe_condition = [0]
    elif user_experience == 1:
        min_yoe_condition = [0, 1]
        max_yoe_condition = [1]
    elif user_experience == 2:
        min_yoe_condition = [0, 2]
        max_yoe_condition = [2]
    else:
        min_yoe_condition = [0, user_experience]
        max_yoe_condition = [user_experience]

    jobs_ex = jobs_a[(jobs_a['min_yoe'].isin(min_yoe_condition)) & (jobs_a['max_yoe'].isin(max_yoe_condition))]
    
    jobs_ex['title'] = jobs_ex['title'].fillna('')
    jobs_ex['description'] = jobs_ex['description'].fillna('')
    jobs_ex['skills'] = jobs_ex['skills'].fillna('')
    jobs_ex['requirement'] = jobs_ex['requirement'].fillna('')
    # new column
    jobs_ex['combine'] = jobs_ex['title'] + " " + jobs_ex['description'] + " " + jobs_ex['skills'] + " " + jobs_ex['requirement']

    jobs_ex['combine'] = jobs_ex['combine'].map(str).apply(clean_txt)

    users['good_at_position'] = users['good_at_position'].fillna('')
    users['skills'] = users['skills'].fillna('')
    users['experiences'] = users['experiences'].fillna('')
    users['achievements'] = users['achievements'].fillna('')
    users['combine'] = users['good_at_position'] + " " + users['skills'] + " " + users['experiences'] + " " + users['achievements']

    users['combine'] = users['combine'].map(str).apply(clean_txt)

    nlp = spacy.load('vi_core_news_lg')
    
    # Get the user's combined text
    user_combine_text = users.loc[users['id'] == user_id, 'combine'].values[0]
    
    # Calculate similarity between user's combine text and jobs' combine text
    similarity_scores = calculate_sim_with_spacy(nlp, jobs_ex, user_combine_text, n=10)
    
    # Sort the similarity scores in descending order based on similarity score
    sorted_scores = sorted(similarity_scores, key=lambda x: x[3], reverse=True)

    # Extract the recommended job IDs and similarity scores
    recommended_jobs = [(score[2], score[3]) for score in sorted_scores]

    # Filter the jobs dataframe based on the recommended job IDs
    recommended_jobs_df = jobs.loc[jobs.index.isin([job[0] for job in recommended_jobs])]

    # Create a list to store the job data
    job_data = []

    # Iterate over the recommended jobs DataFrame
    for job in recommended_jobs_df.itertuples():
        similarity_score = next(score[1] for score in recommended_jobs if score[0] == job.Index)
        job_info = job._asdict()
        job_info['Similarity Score'] = similarity_score
        job_data.append(job_info)

    # Create a DataFrame from the job_data list
    recommended_jobs_data_sorted = pd.DataFrame(job_data)
    recommended_jobs_data_sorted = recommended_jobs_data_sorted.sort_values(by='Similarity Score', ascending=False)

    # Find the highest similarity score
    highest_score = recommended_jobs_data_sorted['Similarity Score'].max()

    # Calculate the minimum score threshold
    min_score = highest_score * 0.92

    # Filter the recommended jobs based on the minimum score threshold
    filtered_jobs = recommended_jobs_data_sorted[recommended_jobs_data_sorted['Similarity Score'] > min_score]

    # Lọc công việc dựa trên danh mục (categories)
    if categories:      
        filtered_jobs = filtered_jobs[filtered_jobs['categories'].str.contains(categories, case=False)]

    return filtered_jobs

# Lấy danh sách người dùng vừa được thêm mới hoặc chỉnh sửa
target_date = datetime.date.today()

# Trích xuất ngày từ cột created_at và updated_at
users['created_date'] = users['created_at'].dt.date
users['updated_date'] = users['updated_at'].dt.date

# Lọc các người dùng có created_date hoặc updated_date khớp với target_date
updated_users = users[(users['created_date'] == pd.to_datetime(target_date).date()) | (users['updated_date'] == pd.to_datetime(target_date).date())]

# Lấy danh sách các ID của người dùng vừa được thêm mới hoặc chỉnh sửa
updated_user_ids = updated_users['id'].tolist()

# Lấy danh sách user ids đã chạy trong MongoDB
mongo_user_ids = set([user['user_id'] for user in collection.find()])

# Lọc ra các user ids chưa có thông tin trong MongoDB
missing_ids = [user_id for user_id in users['id'] if user_id not in mongo_user_ids]

def get_recommendations_from_mongodb(user_id, categories=None):  
    # Find the document based on user_id
    document = collection.find_one({'user_id': user_id})

    # Return the recommended jobs as a JSON response with pagination
    if document:
        recommended_jobs = document['jobs']
        recommended_jobs_df = pd.DataFrame(recommended_jobs)

        # Filter jobs based on categories
        if categories:
            filtered_jobs = recommended_jobs_df[recommended_jobs_df['categories'].str.contains(categories, case=False)]
            if not filtered_jobs.empty:
                recommended_jobs_df = filtered_jobs

        return recommended_jobs_df

    return None

def process_user(user_id, missing_ids, updated_user_ids, categories=None):
    if user_id not in missing_ids and user_id not in updated_user_ids:
        # Connect to MongoDB and get recommendations
        recommended_jobs_mongo = get_recommendations_from_mongodb(user_id, categories)

        if recommended_jobs_mongo is not None:
            # Process recommended jobs from MongoDB
            return recommended_jobs_mongo

    # Call recommend_job function if user_id is in missing_ids or updated_user_ids
    result = recommend_job(user_id, categories)
    return result

@app.get("/recommend-job/{user_id}")
def recommend_job_mongo(
    user_id: int,
    categories: str = None,
    page: int = Query(1, description="Page number"),
    limit: int = Query(10, description="Number of results per page"),
):
    try:
        recommended_jobs = process_user(user_id, missing_ids, updated_user_ids, categories)

        if isinstance(recommended_jobs, str):  # Kiểm tra xem kết quả là chuỗi thông báo lỗi hay không
            error_response = {
                "error": False,
                "message": recommended_jobs,
                "data": None,
                "status_code": 400
            }
            return JSONResponse(status_code=400, content=error_response)

        # Apply pagination
        total_jobs = len(recommended_jobs)
        total_pages = math.ceil(total_jobs / limit)
        start_index = (page - 1) * limit
        end_index = start_index + limit
        paginated_jobs = recommended_jobs[start_index:end_index]

        # Create pagination links
        base_url = f"http://localhost:8001/recommend-job/{user_id}"
        first_page_url = f"{base_url}?categories={categories}&page=1"
        last_page_url = f"{base_url}?categories={categories}&page={total_pages}"
        next_page_url = f"{base_url}?categories={categories}&page={page + 1}" if page < total_pages else None
        prev_page_url = f"{base_url}?categories={categories}&page={page - 1}" if page > 1 else None

        links = [
            {
                "url": prev_page_url,
                "label": "&laquo; Previous",
                "active": False
            },
            {
                "url": first_page_url,
                "label": "1",
                "active": page == 1
            }
        ]

        for i in range(2, total_pages + 1):
            links.append({
                "url": f"{base_url}?categories={categories}&page={i}",
                "label": str(i),
                "active": page == i
            })

        links.append({
            "url": next_page_url,
            "label": "Next &raquo;",
            "active": False
        })

        # Create pagination info
        pagination_info = {
            "first_page_url": first_page_url,
            "last_page_url": last_page_url,
            "links": links,
            "next_page_url": next_page_url,
            "prev_page_url": prev_page_url,
            "current_page": page,
            "total_pages": total_pages,
            "total_jobs": total_jobs
        }

        if len(paginated_jobs) == 0:
            return {
                "error": False,
                "message": "Không tìm thấy công việc",
                "data": None,
                "status_code": 404
            }

        return {
            "error": False,
            "message": "Xử lí thành công",
            "data": {
                "jobs": {
                    "current_page": page,
                    "data": paginated_jobs.to_dict(orient="records"),
                    "pagination_info": pagination_info
                }
            },
            "status_code": 200
        }

    except (ConnectionError, TimeoutError, Timeout) as e:
        # Xử lý lỗi mạng
        return {
            "error": True,
            "message": "Lỗi mạng",
            "data": [],
            "status_code": HTTP_503_SERVICE_UNAVAILABLE
        }
    except (ValueError, TypeError) as e:
        # Xử lý lỗi đầu vào gây crash hoặc lỗi xử lý không mong muốn
        return {
            "error": True,
            "message": "Lỗi đầu vào gây crash",
            "data": [],
            "status_code": status.HTTP_400_BAD_REQUEST
        }
    except Exception as e:
        return {
            "error": True,
            "message": "Lổi đầu vào không hợp lệ/ Lỗi website đang gặp sự cố",
            "data": [],
            "status_code": status.HTTP_500_INTERNAL_SERVER_ERROR
        }

# Hoang
# Define vietnamese component in list
def HasVietnamese(string):
    pattern = re.compile(r'\b\w*[àáạảãăắằẳẵặâấầẩẫậèéẹẻẽêếềểễệìíịỉĩòóọỏõôốồổỗộơớờởỡợùúụủũưứừửữựỳýỵỷỹđ]+\w*\b', re.IGNORECASE)
    if pattern.search(string):
        return True
    return False
# Remove character isn't alphabet
def RemoveNonAlphabet(strings):
    alphabet_pattern = re.compile('[^a-zA-ZÀ-ỹ ]')
    cleaned_strings = []
    for string in strings:
        cleaned_string = alphabet_pattern.sub('', string)
        cleaned_strings.append(cleaned_string)
    return cleaned_strings
# Delete line contains string
def DeleteLine(string, remove):
    lines = string.split('\n')
    filtered_lines = [line for line in lines if remove not in line]
    updated_text = '\n'.join(filtered_lines)
    return updated_text
# Read Information
def ReadBirthday(string):
    birthday = None
    split_n= string.split('\n') # split '\n'
    split_n = [string for string in split_n if string] # delete empty component
    Birthday_pattern_1 = r'\d{2}/\d{2}/\d{4}'
    Birthday_pattern_2 = r'\d{2}-\d{2}-\d{4}'
    Birthday_pattern_3 = r"(?i)(?:January|February|March|April|May|June|July|August|September|October|November|December)\s+\d{1,2},\s+\d{4}"    
    for birthday in split_n:
        if re.search(Birthday_pattern_1, birthday):
            birthday = (re.search(Birthday_pattern_1, birthday)).group(0)
            if (birthday[-4:] <= '2005'):
                return birthday
        if re.search(Birthday_pattern_2, birthday):
            birthday = (re.search(Birthday_pattern_2, birthday)).group(0)
            if (birthday[-4:] <= '2005'):
                return birthday
        if re.search(Birthday_pattern_3, birthday):
            birthday = (re.search(Birthday_pattern_3, birthday)).group(0)
            if (birthday[-4:] <= '2005'):
                return birthday
def ReadPhoneNumber(string):
    number = None
    split_n= string.split('\n') # split '\n'
    split_n = [string for string in split_n if string] # delete empty component
    Phone_pattern = r"[^0-9/]"
    Phone_pattern_1 = r'\b0\d{9}'
    Phone_pattern_2 = r'\b84\d{9}'
    for phone in split_n:
        phone = phone.replace(' ', '') # Delete space
        if re.findall(Phone_pattern_1, phone): # start with 0
            number = re.findall(Phone_pattern_1, phone)
        if number == None: # but have - or . or space between
            phone = re.sub(Phone_pattern, '', phone)
            if re.findall(Phone_pattern_1, phone): # start with 0
                number = re.findall(Phone_pattern_1, phone)
            if re.findall(Phone_pattern_2, phone): # start with 84
                number = re.findall(Phone_pattern_2, phone)
    if number == None:
        return number
    else:
        return number.pop()
def ReadEmail(string):
    email = None
    split_n= string.split('\n') # split '\n'
    split_n = [string for string in split_n if string] # delete empty component
    # Read Email
    for email in split_n:
        if "@gmail" in email and 'mailto' not in email:
            email = email.replace("Email", '') # delete word
            return email
def ReadGithub(string):
    github = None
    split_n= string.split('\n') # split '\n'
    split_n = [string for string in split_n if string] # delete empty component
    # Read Email
    for github in split_n:
        if "github" in github and 'https' in github:
            index = github.find("https")
            github = github[index:]
            return github
def ReadLink(string):
    result = []
    link = None
    split_n= string.split('\n') # split '\n'
    split_n = [string for string in split_n if string] # delete empty component
    # Read Email
    for link in split_n:
        if "www." in link:
            index = link.find("www.")
            link = link[index:]
            result.append(link)
    return result
def ReadContent(string):
    split = string.split('\n') # split '\n'
    split = [string for string in split if string] # delete empty component
    # Read tittle
    tittle = []
    for i in split:
        if i.isupper():
            tittle.append(i)
    # Check form
    if len(tittle) <= 1:
        return [], []
    # Delete non alphabet in tittle
    tittle = RemoveNonAlphabet(tittle)
    # Read name
    name = tittle.pop(0)
    content = []
    # Get content 
    for i in range (0, len(tittle) - 1): # Get content between 2 upper words
        start = tittle[i]
        end = tittle[i + 1]
        pattern = rf"(?<=\b{start}\b)(.*?)(?=\b{end}\b)"
        match = re.search(pattern, string, re.DOTALL)
        if match:
            content_between_words = match.group().strip()
            content.append(content_between_words)
    last = tittle[-1] # Get content of last upper word
    temp = string.split(last, 1)[-1].strip()
    content.append(temp)
    # Number of upper tittle not equal to content
    if len(content) != len(tittle):
        return [], []
    if(len(name.split()) < 3):
        return [], []
    # Check component Contact
    if "CONTACT" not in tittle:
        if "PROFILE" not in tittle:
            return [], []
    # Check Vietnamese
    for i in tittle:
        if HasVietnamese(i):
            return [], []
    # Return default form
    key = ['NAME', 'POSITION', 'PERSONAL']
    value = []
    # Add name
    name = name.replace('\n', '') # Delete '/n'
    value.append(name) 
    # Add position
    if(len(tittle) == len(content)):
        position = tittle.pop(0) 
        position = position.replace('\n', '') # Delete '/n'
        value.append(position)
    else:
        value.append([])
    # Add personal
    personal = content.pop(0)
    personal = personal.replace('\n', '') # Delete '/n'
    value.append(personal)
    # Add another one
    if tittle != None and content != None:
        while tittle:
            temp_1 = tittle.pop()
            temp_2 = content.pop()
            if 'CONTACT' in temp_1 or 'PROFILE:' in temp_1: # Read each information in contact
                # Add birthday
                key.append('BIRTHDAY')
                tmp = ReadBirthday(temp_2)
                if tmp != None:
                    temp_2 = DeleteLine(temp_2, tmp)
                value.append(tmp)
                # Add phone number
                key.append('PHONE')
                tmp = ReadPhoneNumber(temp_2)
                if tmp != None:
                    temp_2 = DeleteLine(temp_2, tmp[-3:])
                    if tmp[:2] == "84": # Change 84 to 0
                        tmp = '0' + tmp[2:]
                value.append(tmp)
                # Add email
                key.append('GMAIL')
                tmp = ReadEmail(temp_2)
                if tmp != None:
                    temp_2 = DeleteLine(temp_2, tmp)
                value.append(tmp)
                # Add github
                key.append('GITHUB')
                tmp = ReadGithub(temp_2)
                if tmp != None:
                    temp_2 = DeleteLine(temp_2, tmp)
                value.append(tmp)
                # Add all link
                key.append('LINK')
                tmp = ReadLink(temp_2)
                if tmp != None:
                    for i in tmp:
                        temp_2 = DeleteLine(temp_2, i)
                value.append(tmp)
                # Add address
                key.append('ADDRESS')
                value.append(temp_2)
            else: # Read information
                key.append(temp_1)
                value.append(temp_2)
    # Return
    return key, value
# Get image
def UploadImage(path):
     # Set Config
    config = {
  "apiKey": os.environ.get("API_KEY"),
  "authDomain": os.environ.get("AUTH_DOMAIN"),
  "databaseURL": os.environ.get("DATABASE_URL"),
  "projectId": os.environ.get("PROJECT_ID"),
  "storageBucket": os.environ.get("STORAGE_BUCKET"),
  "messagingSenderId": os.environ.get("MESSAGING_SENDER_ID"),
  "appId": os.environ.get("APP_ID"),
  "measurementId": os.environ.get("MEASUREMENT_ID")}
    # Set information
    firebase = pyrebase.initialize_app(config)
    storage = firebase.storage() 
    # Upload image
    storage.child(path).put(path)   
    auth = firebase.auth()
    # Information
    email = os.environ.get("EMAIL")
    password = os.environ.get("PASSWORD")
    # Get URL
    user = auth.sign_in_with_email_and_password(email, password)
    url = storage.child(path).get_url(user['idToken'])
    # Hide token of URL
    url = urllib.parse.urlparse(url)
    query_params = urllib.parse.parse_qs(url.query)
    query_params.pop('token', None)
    url = urllib.parse.urlunparse(url._replace(query=urllib.parse.urlencode(query_params, doseq=True)))
    return(url)
def DetectFaces(path):
    # Link URL
    link = ""
    # Convert PDF to images
    images = convert_from_path(path)
    # Face detector 
    face_detector = dlib.get_frontal_face_detector()
    # Convet into MAT from
    image = np.array(images[0])
    # Convert the image to grayscale
    gray_image = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
    # Detect face in grayscale
    faces = face_detector(gray_image)
    # Scale 
    scale_factor = 1.5
    # Check if any faces are detected
    if len(faces) > 0:
        for face in faces:
            x, y, w, h = face.left(), face.top(), face.width(), face.height()
            dlib.rectangle(left=x, top=y, right=x + w, bottom=y + h)
            # Apply scaling factor to the dimensions
            x -= int(w * (scale_factor - 1) / 2)
            y -= int(h * (scale_factor - 1) / 2)
            w = int(w * scale_factor)
            h = int(h * scale_factor)
            # Save cropped face image
            cropped_image = image[max(0, y):y + h, max(0, x):x + w]
            # Convert into RGB channels
            cropped_image = cv2.cvtColor(cropped_image, cv2.COLOR_BGR2RGB)
            # save the cropped face image
            face_image_path = "crop.jpg"
            cv2.imwrite(face_image_path, cropped_image)
            # Upload image and get URL
            link = UploadImage(face_image_path)
            # Remove file at local
            os.remove(face_image_path)
    return link
# Return result
def ExtractTextFromPDF(path):
    key = []
    value = []
    result = ''
    link = DetectFaces(path)
    raw_text = parser.from_file(path)
    text = raw_text['content']
    key, value = ReadContent(text)
    if len(key) != 0 and len(value) != 0:
        # Get name
        name = about = position = birthday = address= email = phone = ''
        education = experience = achievement = skill = github = activity = info = ''
        tmp = None # None value
        for i in range(0, len(key)):
            if(key[i] == "NAME"):
                name = value[i]
                continue
            if(key[i] == "PERSONAL"):
                about = value[i]
                continue 
            if(key[i] == "POSITION"):
                position = value[i]
                continue     
            if(key[i] == "BIRTHDAY"):
                birthday = value[i]
                continue
            if(key[i] == "ADDRESS"):
                address = value[i] 
                continue
            if(key[i] == "GMAIL"):
                email = value[i]  
                continue
            if(key[i] == "PHONE"):
                phone = value[i] 
                continue
            if(key[i] == "UNIVERSITY PROJECTS"):
                education = value[i]  
                continue
            if(key[i] == "WORK EXPERIENCE"):
                experience = value[i]  
                continue
            if(key[i] == "ACHIEVEMENTS"):
                achievement = value[i]  
                continue
            if(key[i] == "SKILLS"):
                skill = value[i]  
                continue
            if(key[i] == "GITHUB"):
                github = value[i]  
                continue
            if(key[i] == "ACTIVITIES"):
                activity = value[i]  
                continue
            if(key[i] == "LINK"):
                info = value[i]  
                continue
        result = {
        "error": False,
        "message": "Trích xuất thông tin CV thành công",
        "data":{
            "user_profile":{
                "id": '',
                "full_name": name,
                "avatar": link,
                "about_me": about,
                "good_at_position": position,
                "year_of_experience": tmp,
                "date_of_birth": birthday,
                "gender": tmp,
                "address": address,
                "email": email,
                "phone": phone,
                "created_at": tmp,
                "updated_at": tmp,
                "educations": education,
                'cvs': tmp,
                "experiences": experience,
                "achievements": achievement,
                "skills" : skill,
                "time_tables": tmp,
                "github" : github, 
                "activities": activity,
                "link": info
            },
            "status_code": 200
        }
        }
    else: 
        result ={
            "error": True,
            "message": "Định dạng CV chưa được hỗ trợ",
            "data": ''
        }
    return JSONResponse(content=result)

# Read_CV
def cleanup_temp_directory():
    if os.path.exists(UPLOAD_DIRECTORY):
        shutil.rmtree(UPLOAD_DIRECTORY)

UPLOAD_DIRECTORY = "temp_uploads"

@app.post("/read-cv")
async def read_cv(file: UploadFile = File(...)):
    try:
        if not os.path.exists(UPLOAD_DIRECTORY):
            os.makedirs(UPLOAD_DIRECTORY)
        file_path = os.path.join(UPLOAD_DIRECTORY, file.filename)
        with open(file_path, "wb") as buffer:
            buffer.write(file.file.read())
        result = ExtractTextFromPDF(file_path)
        os.remove(file_path)
        return result
    except Exception as e:
        return {
            "success": False,
            "message": str(e)
       }
    finally:
        cleanup_temp_directory()
        
# Detect_Faces
@app.post("/detect-faces")
async def detect_faces(image: UploadFile = File(...)):
    try:
        # Read the uploaded image file
        image_data = await image.read()
        nparr = np.frombuffer(image_data, np.uint8)
        img = cv2.imdecode(nparr, cv2.IMREAD_COLOR)
        # Face detector 
        face_detector = dlib.get_frontal_face_detector()
        # Convert to grayscale
        gray_image = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
        # Detecting
        faces = face_detector(gray_image)
        # Check if any faces are detected
        if len(faces) > 0:
            bool_result = True
        else:
            bool_result = False
        # Return the boolean result as JSON response
        return JSONResponse({
            "error": False,
            "message": bool_result,})
    except Exception as e:
        return JSONResponse({"error": True,
                            "messsage": str(e)})
