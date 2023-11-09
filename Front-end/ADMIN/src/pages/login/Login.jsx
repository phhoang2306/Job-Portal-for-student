import axios from 'axios';
import React from 'react';
import { useContext } from 'react';
import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { AuthContext } from '../../contexts/AuthContext.js';
import {localUrl} from '../../utils/path.js'
import "./login.scss";

const Login = ( ) => {
    const [credentials, setCredentials] = useState({
        username: undefined,
        password: undefined,
    });    
    const { loading, error, dispatch } = useContext(AuthContext);

    const navigate = useNavigate();

    const handleChange = (e)=>{    
        setCredentials(prev=>({...prev, [e.target.id]: e.target.value })); 
    }
                                   
    const handleClick = async (e) => {
      e.preventDefault();
      dispatch({type: "LOGIN_START"});
      try {
        const res = await axios.post(`${localUrl}/auth-admin/sign-in`, credentials);
        if (res.data.data.admin.is_banned === 0 && res.data.data.admin.locked_until === null ) {
          dispatch({
            type: "LOGIN_SUCCESS",
            payload: {
              user: res.data.data.admin,
              token: res.data.data.token
            },
          });
          navigate("/");
        } else {
          dispatch({type: "LOGIN_FAILURE", payload: {message: "Tài khoản của bạn đã bị khóa!"}});
        }
      } catch (err) {
        dispatch({type: "LOGIN_FAILURE", payload: err.response ? err.response.data : err.message});
      }
    }
    


    return (
      <body class="login-body">
      <div className="container">
        <h1>Đăng nhập Admin</h1>
        <form>
          <input
            type="text"
            placeholder="Nhập tên đăng nhập"
            id="username"
            onChange={handleChange}
            className="lInput"
            style={{marginBottom: '20px'}}
            required
          />
          <input
            type="password"
            placeholder="Nhập mật khẩu:"
            id="password"
            onChange={handleChange}
            className="lInput"
            style={{marginBottom: '20px'}}
            required
          />
          <button
            style={{width: '100%', height: '50px', fontSize: '24px'}}
            disabled={loading}
            onClick={handleClick}
            className="lButton"
            type="submit"
          >
            Đăng nhập
          </button>
          {error && <span>{error.message}</span>}
        </form>
      </div>
    </body>
    );
    
};
    

export default Login;