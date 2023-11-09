# findev

## Hướng dẫn cài Elasticsearch
Bước 1: Cài docker
Truy cập trang chủ Docker (https://www.docker.com/) và tải xuống phiên bản phù hợp với hệ điều hành của bạn

Mở Command Prompt hoặc PowerShell

Bước 2:Chạy lệnh sau để tải và cài đặt Elasticsearch image từ Docker Hub:
     
     docker pull docker.elastic.co/elasticsearch/elasticsearch:7.7.0
  Lưu ý rằng đây là phiên bản Elasticsearch 7.7.0. Bạn có thể thay đổi phiên bản Elasticsearch tương ứng với yêu cầu của bạn

Bước 3: Tạo một container Elasticsearch 
     
     docker run -d --name elasticsearch -p 9201:9200 -e "discovery.type=single-node" docker.elastic.co/elasticsearch/elasticsearch:7.7.0

Kiểm tra lại trang thái container:
     
     docker ps
  Bạn sẽ thấy container Elasticsearch hiện đang chạy
  
Kiểm tra kết quả cài đặt Elasticsearch bằng cách mở trính duyệt web truy cập vào 
     
     http://localhost:9201
  Nếu kết quả như sau:

{
  "name" : "b8115cf3da64",
  "cluster_name" : "docker-cluster",
  "cluster_uuid" : "aL8-wRiTQdq6lL8bHQ9Njg",
  "version" : {
    "number" : "7.7.0",
    "build_flavor" : "default",
    "build_type" : "docker",
    "build_hash" : "81a1e9eda8e6183f5237786246f6dced26a10eaf",
    "build_date" : "2020-05-12T02:01:37.602180Z",
    "build_snapshot" : false,
    "lucene_version" : "8.5.1",
    "minimum_wire_compatibility_version" : "6.8.0",
    "minimum_index_compatibility_version" : "6.0.0-beta1"
  },
  "tagline" : "You Know, for Search"
}

Là bạn đã cài thành công

## Tải vi_spacy
Mở Command Prompt hoặc PowerShell nhập lệnh
    
    pip install https://gitlab.com/trungtv/vi_spacy/-/raw/master/vi_core_news_lg/dist/vi_core_news_lg-0.0.1.tar.gz


## Hướng dẫn run FastAPI
Bước 1: Cài các thư việc trong requirements.txt như sau
```
pip3 install -r requirements.txt
```

Bước 2: Mở Command Prompt hoặc PowerShell nhập lệnh
    
    uvicorn main:app --reload --port 8001

để truy cập vào doc thì click vào:
    http://127.0.0.1:8001/docs#/
    

