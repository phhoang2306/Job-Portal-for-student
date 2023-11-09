import React, { useState, useEffect } from "react";
import { localUrl } from "../../utils/path";
import axios from "axios";
import { AuthContext } from "../../contexts/AuthContext";
import { useContext } from 'react';
import {Typography } from "@mui/material";
import Select from 'react-select';
import {skillOptions, cateOptions} from './Options'

const PostJob = () => {
  const [title, setTitle] = useState("");
  const [skills, setSkills] = useState("");
  const [cates, setCates] = useState("");
  const [total, setTotal] = useState("");
  const [benefit, setBenefit] = useState("");
  const [maxyoe, setMaxyoe] = useState("");
  const [minyoe, setMinyoe] = useState("");
  const [minSalary, setMinSalary] = useState("");
  const [maxSalary, setMaxSalary] = useState("");
  const [requirement, setRequirement] = useState("");
  const [position, setPosition] = useState("");
  const [description , setDescription ] = useState("");
  const [address, setAddress] = useState("");
  const [gender, setGender] = useState("");
  const [type, setType] = useState("");
  const [deadline, setDeadline] = useState("");
  const {user, token } = useContext(AuthContext);

  const handleSkillChange = (selectedOptions) => {
    const selectedSkills = selectedOptions.map((option) => option.value).join(';');;
    setSkills(selectedSkills);
  };

  const handleCateChange = (selectedOptions) => {
    const selectedCates = selectedOptions.map((option) => option.value).join(';');
    setCates(selectedCates);
  };

  const CreateJobSkill = async (jobId) => {
    try {

      await axios.post(`${localUrl}/job-skills`, 
        { 
          "skill": skills,
          "job_id": jobId, 
        }, 
        {
        headers: {
          'Content-Type': 'application/json',
          'Authorization': token
        },
      });
    } catch (error) {
    }
  };

  const CreateJobCate = async (jobId) => {
    try {

      await axios.post(`${localUrl}/job-categories`, 
        { 
          "category_id": cates,
          "job_id": jobId, 
        }, 
        {
        headers: {
          'Content-Type': 'application/json',
          'Authorization': token
        },
      }); 
    } catch (error) {
    }
  };
  
  
  const handleSubmit = async (event) => {
    event.preventDefault();
    try {
      const res = await axios.post(`${localUrl}/jobs`, 
        {
          'title': title,
          'description': description,
          'benefit': benefit,
          'requirement': requirement,
          'location': address,
          'min_salary': minSalary,
          'max_salary': maxSalary,
          'recruit_num': total,
          'position': position,
          'min_yoe': minyoe,
          'max_yoe': maxyoe,
          'gender': gender,
          'deadline': deadline,
          'type': type,
        },
        {
          headers: 
          {
            'Content-Type': 'application/json',
            'Authorization': token
          }
        }
      );
      if (res.data && res.data.data.job.id) {
        const jobId = res.data.data.job.id;
        await CreateJobSkill(jobId);
        await CreateJobCate(jobId);
      }
      alert("Công việc được đăng thành công!");
      setTitle("");
      setTotal("");
      setBenefit("");
      setMaxyoe("");
      setMinyoe("");
      setMinSalary("");
      setMaxSalary("");
      setRequirement("");
      setAddress("");
      setGender("");
      setType("");
      setDeadline("");
      setPosition("");
      setDescription("");
    } catch (error) {
      alert(error.response.data.message);
    }
  };

  function getToday() {
      const today = new Date();
      const day = String(today.getDate()).padStart(2, "0");
      const month = String(today.getMonth() + 1).padStart(2, "0");
      const year = today.getFullYear();
      return `${year}-${month}-${day}`;
  }



  return (
    <form className="default-form job-apply-form" onSubmit={handleSubmit}>
    <Typography sx={{mb: 2}} variant="h5">Đăng tuyển</Typography>

    <div className="row">

        <label> Tên công việc (ít nhất 10 ký tự) </label><span style={{color:"red"}}> *</span>
        <div className="col-lg-6 col-md-6 col-sm-6 form-group">
            <input
            type="text"
            className="form-control"
            value={title}
            onChange={(e) => setTitle(e.target.value)}
            required
            />
        </div>

        <label> Địa điểm làm việc </label><span style={{color:"red"}}> *</span>
        <div className="col-lg-6 col-md-6 col-sm-6 form-group">
        <select
        style={{ width: '300px' }}
        className="form-control"
        value={address}
        onChange={(e) => setAddress(e.target.value)}
        required
      >
        <option value="">Chọn địa điểm làm việc</option>
        <option value="Thành phố Hồ Chí Minh">Thành phố Hồ Chí Minh</option>
        <option value="Hà Nội">Hà Nội</option>
      </select>
        </div>

        <label> Mô tả công việc </label><span style={{color:"red"}}> *</span>
        <div className="col-lg-6 col-md-6 col-sm-6 form-group">
          <textarea
            className="form-control"
            value={description}
            onChange={(e) => setDescription(e.target.value)}
            required
          />
        </div>


        <label> Phúc lợi nhận được (phải có ít nhất 10 ký tự)</label><span style={{color:"red"}}> *</span>
        <div className="col-lg-6 col-md-6 col-sm-6 form-group">
          <textarea
            className="form-control"
            value={benefit}
            onChange={(e) => setBenefit(e.target.value)}
            required
          />
        </div>

        <label> Yêu cầu công việc </label><span style={{color:"red"}}> *</span>
        <div className="col-lg-6 col-md-6 col-sm-6 form-group">
          <textarea
            className="form-control"
            value={requirement}
            onChange={(e) => setRequirement(e.target.value)}
            required
          />
        </div>

        <label> Hình thức làm việc </label><span style={{color:"red"}}> *</span>
        <div className="col-lg-6 col-md-6 col-sm-6 form-group">
        <select
          className="form-control"
          value={type}
          onChange={(e) => setType(e.target.value)}
          required
        >
          <option value="">Chọn hình thức làm việc</option>
          <option value="Thực tập">Thực tập</option>
          <option value="Toàn thời gian">Toàn thời gian</option>
          <option value="Bán thời gian">Bán thời gian</option>
        </select>
      </div>


        <label> Tiền lương (Triệu VND) </label><span style={{color:"red"}}> *</span>
        <div className="col-lg-6 col-md-6 col-sm-6 form-group" style={{ display: 'flex' }}>
            <input
            type="number"
            className="form-control"
            placeholder="Thấp nhất"
            value={minSalary}
            min="0"
            onChange={(e) => setMinSalary(e.target.value)}
            style={{ width: '150px' }}
            required
            />

            <input
            type="number"
            className="form-control"
            placeholder="Cao nhất"
            style={{ width: '150px' }}
            value={maxSalary}
            min="0"
            onChange={(e) => setMaxSalary(e.target.value)}
            required
            />
        </div>
        
        <label> Số lượng tuyển </label><span style={{color:"red"}}> *</span>
        <div className="col-lg-6 col-md-6 col-sm-6 form-group">
            <input
            type="number"
            min="1"
            style={{ width: '150px' }}
            className="form-control"
            value={total}
            onChange={(e) => setTotal(e.target.value)}
            required
            />
        </div>

        <label>Vị trí tuyển dụng</label><span style={{color:"red"}}> *</span>
        <div className="col-lg-6 col-md-6 col-sm-6 form-group">
            <input
            type="text"
            style={{ width: '400px' }}
            className="form-control"
            value={position}
            onChange={(e) => setPosition(e.target.value)}
            required
            />
        </div>

        <label> Kinh nghiệm (năm)</label><span style={{color:"red"}}> *</span>
        <div className="col-lg-6 col-md-6 col-sm-6 form-group" style={{ display: 'flex' }}>
            <input
            type="number"
            className="form-control"
            placeholder="Thấp nhất"
            value={minyoe}
            min="0"
            onChange={(e) => setMinyoe(e.target.value)}
            style={{ width: '150px' }}
            required
            />

            <input
            type="number"
            className="form-control"
            placeholder="Cao nhất"
            style={{ width: '150px' }}
            value={maxyoe}
            min="0"
            onChange={(e) => setMaxyoe(e.target.value)}
            required
            />
        </div>
           
        <label> Giới tính</label><span style={{color:"red"}}> *</span>
        <div className="col-lg-6 col-md-6 col-sm-6 form-group">
          <select
            style={{ width: '150px' }}
            className="form-control"
            value={gender}
            onChange={(e) => setGender(e.target.value)}
            required
          >
            <option value="">Giới tính</option>
            <option value="Nam">Nam</option>
            <option value="Nữ">Nữ</option>
          </select>
        </div>

        <label>Kỹ năng</label> <span style={{color:"red"}}> *</span>
        <div className="col-lg-6 col-md-6 col-sm-6 form-group">
        <p>Chọn tối đa 5 kỹ năng</p>
        <Select
          defaultValue={[]}
          isMulti
          name="skills"
          options={skillOptions}
          className="basic-multi-select"
          classNamePrefix="select"
          onChange={handleSkillChange}
          placeholder="Lựa chọn"
        />
        </div>

        <label>Danh mục</label> <span style={{color:"red"}}> *</span>
        <div className="col-lg-6 col-md-6 col-sm-6 form-group">
        <p>Chọn tối đa 3 danh mục</p>
        <Select
          defaultValue={[]}
          isMulti
          name="cates"
          options={cateOptions}
          className="basic-multi-select"
          classNamePrefix="select"
          onChange={handleCateChange}
          placeholder="Lựa chọn"
        />
        </div>

        <label>Hạn chót tuyển</label><span style={{color:"red"}}> *</span>
        <div className="col-lg-6 col-md-6 col-sm-6 form-group">
            <input
            type="date"
            className="form-control"
            value={deadline}
            min={getToday()}
            onChange={(e) => setDeadline(e.target.value)}
            required
            />
        </div>
  
        <div className="col-lg-12 col-md-12 col-sm-12 form-group">
          <button className="theme-btn btn-style-one w-100" type="submit" name="submit-form">
            Đăng tuyển
          </button>
        </div>
      </div>
    </form>
  );
};

export default PostJob;
