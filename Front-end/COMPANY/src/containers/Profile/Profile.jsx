import { useState, useEffect, useCallback } from "react";
import { AuthContext } from "../../contexts/AuthContext";
import { useContext } from 'react';
import { localUrl } from '../../utils/path';
import fetchCompany from './components/fetchCompany';
import fetchEmployer from './components/fetchEmployer';
import putProfile from './components/putProfile';
import {UploadImg} from './components/UploadImg';
import {
  Box,
  Button,
  Card,
  CardActions,
  CardContent,
  CardHeader,
  Divider,
  TextField,
  Unstable_Grid2 as Grid
} from '@mui/material';


const Profile = () => {
  const { user, role, token } = useContext(AuthContext);
  const [loading, setLoading] = useState(true);
  const [profile, setProfile] = useState(null);
  const [defaultProfile, setDefaultProfile] = useState(null);
  const [modifiedFields, setModifiedFields] = useState({});
  const [selectedImage, setSelectedImage] = useState(null);
  const [avt, setAvt] = useState(null);

  
  const handleAvtChange = (event) => {
    const file = event.target.files[0];
    ////console.log(file)
    if (file && file.type.startsWith("image/")) {
      setAvt(URL.createObjectURL(file));
      UploadImg(file, user, token);
    } else {
      alert("Vui lòng chọn file ảnh");
    }
  };

  let avtImgContent = null;
  if (avt) {
    avtImgContent = (
      <img
        id="avt"
        style={{
          width: "150px",
          height: "150px",
          borderRadius: "50%",
          objectFit: "cover",
          border: "1px solid #ccc",
        }}
        src={avt}
        alt="avatar"
      />
    );
  }


  const fetchData = async () => {
    // //conso.log(role)
    // if (role === "1") {
    //   const companyID = await fetchEmployer(user.id, token);
    //   const data = await fetchCompany(companyID, token);
    //   setLoading(!loading);
    //   setProfile(data.data.company_profile);
    //   setDefaultProfile(data.data.company_profile)
    // } else if (role === "0") {
      const data = await fetchCompany(user.id, token);
      setLoading(!loading);
      setProfile(data.data.company_profile);
      setAvt(data.data.company_profile.logo)
      setDefaultProfile(data.data.company_profile)
      ////conso.log(user.id)
    //}
  };


  useEffect(() => {
    fetchData();
  }, []);

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setModifiedFields((prevFields) => ({
      ...prevFields,
      [name]: value,
    }));
  };

  const handleSubmit = async (event) => {
    event.preventDefault();
    if (Object.keys(modifiedFields).length > 0) {
      const msg = await putProfile(token, modifiedFields);
      if (msg?.error === false) {
        const updatedProfile = { ...profile, ...modifiedFields };
        setProfile(updatedProfile);
        alert(msg.message);
        setModifiedFields({});
      } else {
        alert(msg.message);
      }
    }
  };

  const handleCancel = (event) => {
    event.preventDefault();
    if (Object.keys(modifiedFields).length > 0) {
      setModifiedFields({});
      setProfile(defaultProfile);
    }
  };

  
  return (
  <>
  <div className="widget-content">
        <div
        style={{
          width: "150px",
          height: "150px",
          borderRadius: "50%",
          cursor: "pointer",
          position: "relative",
          overflow: "hidden",
          display: "flex",
          justifyContent: "center",
          alignItems: "center",
        }}
        onClick={() => document.getElementById("uploadImg").click()}
      >
        {avtImgContent}
        <div
          style={{
            position: "absolute",
            bottom: "0",
            left: "0",
            width: "100%",
            height: "30%",
            backgroundColor: "rgba(0, 0, 0, 0.5)",
          }}
        />
        <div
          style={{
            position: "absolute",
            bottom: "10%",
            width: "100%",
            textAlign: "center",
            color: "white",
            fontWeight: "bold",
            textShadow: "2px 2px 4px rgba(0, 0, 0, 0.6)",
          }}
        >
          Thay Avatar
        </div>
      </div>
      <input
        className="uploadButton-input"
        type="file"
        name="attachments[]"
        accept="image/png, image/jpg, image/jpeg"
        id="uploadImg"
        hidden
        onChange={handleAvtChange}
      />
      <br />

      <form action="#" className="default-form ">
        <div className="row" >

          {/* <!-- Input --> */}
          <div className="form-group col-lg-6 col-md-12">
            <label>Tên công ty</label>
            <input type="text" name="name" placeholder={profile?.name || "Vui lòng cập nhật thông tin"} 
            value={modifiedFields.name !== undefined ? modifiedFields.name : (profile?.name || "")}
            onChange={handleInputChange}
            />
          </div>

          {/* <!-- Input --> */}
          <div className="form-group col-lg-6 col-md-12">
            <label>Số điện thoại</label>
            <input
              type="text"
              name="phone"
              placeholder={modifiedFields.phone || profile?.phone || "Vui lòng cập nhật thông tin"}
              value={modifiedFields.phone !== undefined ? modifiedFields.phone : (profile?.phone || "")}
              onChange={handleInputChange}
            />
          </div>

          {/* <!-- Input --> */}
          <div className="form-group col-lg-6 col-md-12">
            <label>Địa chỉ email</label>
            <input
              type="text"
              name="email"
              placeholder={modifiedFields.email || profile?.email || "Vui lòng cập nhật thông tin"}
              value={modifiedFields.email !== undefined ? modifiedFields.email : (profile?.email || "")}
              onChange={handleInputChange}
              
            />
          </div>

          {/* <!-- Input --> */}
          <div className="form-group col-lg-6 col-md-12">
            <label>Địa chỉ</label>
            <input
              type="text"
              name="address"
              placeholder={modifiedFields.address || profile?.address || "Vui lòng cập nhật thông tin"}
              value={modifiedFields.address !== undefined ? modifiedFields.address : (profile?.address || "")}
              onChange={handleInputChange}
            />
          </div>

          {/* <!-- Input --> */}
          <div className="form-group col-lg-6 col-md-12">
            <label>Kích thước công ty</label>
            <input type="text" name="size" 
            placeholder={modifiedFields.size || profile?.size || "Vui lòng cập nhật thông tin"}
            value={modifiedFields.size !== undefined ? modifiedFields.size : (profile?.size || "")}
            onChange={handleInputChange}
            />
          </div>

          {/* <!-- Input --> */}
          <div className="form-group col-lg-6 col-md-12">
            <label>Website công ty</label>
            <input className="chosen-single form-select"
            type="text"
            name="site"
            placeholder={modifiedFields.site || profile?.site || "Vui lòng cập nhật thông tin"}
            value={modifiedFields.site !== undefined ? modifiedFields.site : (profile?.site || "")}
            onChange={handleInputChange}
            >
            </input>
          </div>
          
          {/* <!-- About Company --> */}
          <div className="form-group col-lg-12 col-md-12">
          <label>Mô tả</label>
          <textarea
            placeholder="Vui lòng cập nhật thông tin"
            value={modifiedFields.description !== undefined ? modifiedFields.description : (profile?.description || "")}
            name="description"
            onChange={handleInputChange}
          ></textarea>
        </div>
        {Object.keys(modifiedFields).length > 0 && (
          <div className="form-group col-lg-6 col-md-12">
            <button
              type="submit"
              className="theme-btn btn-style-cancel"
              onClick={handleCancel}
            >
              Hủy
            </button>
            <span style={{ margin: '0 10px' }}></span>
            <button
              type="submit"
              className="theme-btn btn-style-one"
              onClick={handleSubmit}
            >
              Lưu
            </button>
          </div>
        )}

        </div>
      </form>
    </div>
  </>
  );
};

export default Profile;
