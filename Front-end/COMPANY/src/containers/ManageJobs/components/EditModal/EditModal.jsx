import * as React from 'react';
import Button from '@mui/material/Button';
import TextField from '@mui/material/TextField';
import Dialog from '@mui/material/Dialog';
import DialogActions from '@mui/material/DialogActions';
import DialogContent from '@mui/material/DialogContent';
import DialogContentText from '@mui/material/DialogContentText';
import DialogTitle from '@mui/material/DialogTitle';
import EditIcon from '@mui/icons-material/Edit';
import axios from "axios";
import {localUrl} from '../../../../utils/path';
import {useState, useEffect} from 'react';
import {Typography } from "@mui/material";
import Select from 'react-select';
import {skillOptions, cateOptions} from './Options'

export default function EditModal({job_id,token}) {
  const [job, setJob] =useState("")
  const [open, setOpen] = useState(false);
  const [title, setTitle] = useState("");
  const [skills, setSkills] = useState("");
  const [defSkills, setDefSkills]=useState([])
  const [defCates, setDefCates]=useState([])
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


  const handleClickOpen = () => {
    setOpen(true);
  };

  const handleClose = () => {
    setOpen(false);
  };


  const fetchJob = async () => {
    try {
      const res = await axios.get(`${localUrl}/jobs/${job_id}`);
      setTitle(res.data.data.job.title);
      setDeadline(res.data.data.job.deadline);
      setTotal(res.data.data.job.recruit_num);
      setType(res.data.data.job.type);
      setGender(res.data.data.job.gender);
      setAddress(res.data.data.job.location);
      setPosition(res.data.data.job.position);
      setDescription(res.data.data.job.description);
      setRequirement(res.data.data.job.requirement);
      setMinSalary(res.data.data.job.title);
      setMaxSalary(res.data.data.job.title);
      setMaxyoe(res.data.data.job.max_yoe);
      setMinyoe(res.data.data.job.min_yoe);
      setBenefit(res.data.data.job.benefit);
      setDefSkills(res.data.data.job.skills)
      setDefCates(res.data.data.job.categories)
    } catch (error) {
  };
}

  useEffect(() => {
    fetchJob();
  }, [open]);
  
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

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const res = await axios.put(`${localUrl}/jobs/${job_id}`, 
        {
          'title': title,
          'description': description,
          'benefit': benefit,
          'requirement': requirement,
          'location': address,
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
      await CreateJobSkill(job_id);
      await CreateJobCate(job_id);
      alert("Công việc được cập nhật thành công!");
      handleClose();
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
    <>
        <Button
        title="Chỉnh sửa công việc"
        variant="outlined"
        style={{ color: "green", backgroundColor: "white", borderColor: "green" }}
        onClick={handleClickOpen}
        >
        <EditIcon/>
        </Button>
      <Dialog open={open}
        sx={{
          '& .MuiDialog-paper': {
            width: '100%',
            height: '100%',
            margin: 0,
            borderRadius: 0,
            overflow: 'hidden',
            display: 'flex',
            flexDirection: 'column',
          },
        }}
       onClose={handleClose}>
     
        <DialogTitle>Chỉnh sửa công việc</DialogTitle>
        <DialogContent>
        <div className="row">
          <label> Tên công việc (ít nhất 10 ký tự) </label><span style={{color:"red"}}> *</span>
          <div className="col-lg-6 col-md-6 col-sm-6 form-group">
              <input
              type="text"
              style={{ 
                width: '100%' ,
                border: '1px solid black',
                backgroundColor: 'lightgrey',
              }}
              className="form-control"
              value={title}
              onChange={(e) => setTitle(e.target.value)}
              required
              />
          </div>

          <label> Địa điểm làm việc </label><span style={{color:"red"}}> *</span>
          <div className="col-lg-6 col-md-6 col-sm-6 form-group">
          <select
          style={{ 
            width: '400px' ,
            border: '1px solid black',
            backgroundColor: 'lightgrey',
          }}
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
              style={{ 
                width: '100%' ,
                border: '1px solid black',
                backgroundColor: 'lightgrey',
              }}
              value={description}
              onChange={(e) => setDescription(e.target.value)}
              required
              rows={25}
            />
          </div>


          <label> Phúc lợi nhận được (phải có ít nhất 10 ký tự)</label><span style={{color:"red"}}> *</span>
          <div className="col-lg-6 col-md-6 col-sm-6 form-group">
            <textarea
              className="form-control"
              style={{ 
                width: '100%' ,
                border: '1px solid black',
                backgroundColor: 'lightgrey',
              }}
              value={benefit}
              onChange={(e) => setBenefit(e.target.value)}
              required
              rows={25}
            />
          </div>

          <label> Yêu cầu công việc </label><span style={{color:"red"}}> *</span>
          <div className="col-lg-6 col-md-6 col-sm-6 form-group">
            <textarea
              className="form-control"
              style={{ 
                width: '100%' ,
                border: '1px solid black',
                backgroundColor: 'lightgrey',
              }}
              value={requirement}
              onChange={(e) => setRequirement(e.target.value)}
              required
              rows={25}
            />
          </div>

          <label> Hình thức làm việc </label><span style={{color:"red"}}> *</span>
          <div className="col-lg-6 col-md-6 col-sm-6 form-group">
          <select
            className="form-control"
            style={{ 
              border: '1px solid black',
              backgroundColor: 'lightgrey',
            }}
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

          
          <label> Số lượng tuyển </label><span style={{color:"red"}}> *</span>
          <div className="col-lg-6 col-md-6 col-sm-6 form-group">
              <input
              type="number"
              min="1"
              style=
              {{ 
                width: '150px',
                border: '1px solid black',
                backgroundColor: 'lightgrey', 
              }}
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
              style={{ width: '400px',border: '1px solid black',
              backgroundColor: 'lightgrey',  }}
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
              style={{ width: '50px',border: '1px solid black',
              backgroundColor: 'lightgrey', }}
              required
              />

              <input
              type="number"
              className="form-control"
              placeholder="Cao nhất"
              style={{ width: '50px',border: '1px solid black',
              backgroundColor: 'lightgrey', }}
              value={maxyoe}
              min="0"
              onChange={(e) => setMaxyoe(e.target.value)}
              required
              />
          </div>
            
          <label> Giới tính</label><span style={{color:"red"}}> *</span>
          <div className="col-lg-6 col-md-6 col-sm-6 form-group">
            <select
              style={{ width: '150px',border: '1px solid black',
              backgroundColor: 'lightgrey', }}
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
          <Select
          defaultValue={
            defSkills.map((skill) => ({
              value: skill.skill, 
              label: skill.skill,
            })) ?? []
          }
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
          <Select
          defaultValue={
            defCates.map((cate) => ({
              value: cate.id, 
              label: cate.description,
            })) ?? []
          }
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
          <div className="col-lg-6 col-md-6 col-sm-6 form-group mt-10">
              <input
              type="date"
              style={{ width: '150px',border: '1px solid black',
              backgroundColor: 'lightgrey', }}
              className="form-control"
              value={deadline}
              min={getToday()}
              onChange={(e) => setDeadline(e.target.value)}
              required
              />
          </div>
        </div>
        </DialogContent>
        <DialogActions>
          <Button onClick={handleClose}>Hủy</Button>
          <Button onClick={handleSubmit}>Gửi</Button>
        </DialogActions>
      </Dialog>
    </>
  );
}
