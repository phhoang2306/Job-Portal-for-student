import axios from 'axios';
import React from 'react';
import { useContext } from 'react';
import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { AuthContext } from '../../contexts/AuthContext';
import {localUrl} from '../../utils/path'
import Box from "@mui/material/Box";
import Grid from "@mui/material/Grid";
import bg from "../bg/login.png";
import bgimg from "../bg/backimg.jpg";
import Button from "@mui/material/Button";
import TextField from "@mui/material/TextField";
import Typography from "@mui/material/Typography";
import Container from "@mui/material/Container";
import Avatar from "@mui/material/Avatar";
import LockOutlinedIcon from "@mui/icons-material/LockOutlined";
import { ThemeProvider, createTheme } from "@mui/material/styles";
import { forwardRef } from "react";
import Stack from "@mui/material/Stack";
import Slide from "@mui/material/Slide";


const darkTheme = createTheme({
  palette: {
    mode: "dark",
  },
});

const boxstyle = {
  position: "absolute",
  top: "50%",
  left: "50%",
  transform: "translate(-50%, -50%)",
  width: "75%",
  height: "70%",
  bgcolor: "background.paper",
  boxShadow: 24,
};

const center = {
  position: "relative",
  top: "50%",
  left: "37%",
};


const Login = ( ) => {
    const { loading, error, dispatch } = useContext(AuthContext);
    const navigate = useNavigate();

                                   
    const handleSubmit = async (e) => {
      e.preventDefault();
      const data = new FormData(e.currentTarget);
      dispatch({type: "LOGIN_START"});
      try {
        const res = await axios.post(`${localUrl}/auth-company/sign-in`, 
          {
            "password" : data.get("password"),
            "username" : data.get("username"),
          });
        if (res.data.data.companyAccount.is_banned === 0 && res.data.data.companyAccount.locked_until === null ) {
          dispatch({
            type: "LOGIN_SUCCESS",
            payload: {
              user: res.data.data.companyAccount,
              token: res.data.data.token,
              role: 0,
            },
          });
          navigate("/");
        } else {
          dispatch({type: "LOGIN_FAILURE", payload: {message: "Tài khoản của bạn đã bị khóa!"}});
          alert("Tài khoản của bạn đã bị khóa!");
        }
      } catch (err) {
        dispatch({type: "LOGIN_FAILURE", payload: {message: "Đã có lỗi xảy ra"}});
        alert(err.response.data.message);
      }
    }

    
      return (
        <>
          <div
            style={{
              backgroundImage: `url(${bgimg})`,
              backgroundSize: "cover",
              height: "100vh",
              color: "#f5f5f5",
            }}
          >
            <Box sx={boxstyle}>
              <Grid container>
                <Grid item xs={12} sm={12} lg={6}>
                  <Box
                    style={{
                      backgroundImage: `url(${bg})`,
                      backgroundSize: "cover",
                      marginTop: "40px",
                      marginLeft: "15px",
                      marginRight: "15px",
                      height: "63vh",
                      color: "#f5f5f5",
                    }}
                  ></Box>
                </Grid>
                <Grid item xs={12} sm={12} lg={6}>
                  <Box
                    style={{
                      backgroundSize: "cover",
                      height: "70vh",
                      minHeight: "500px",
                      backgroundColor: "#3b33d5",
                    }}
                  >
                    <ThemeProvider theme={darkTheme}>
                      <Container>
                        <Box height={35} />
                        <Box sx={center}>
                          <Typography component="h1" variant="h4">
                            Đăng nhập
                          </Typography>
                        </Box>
                        <Box
                          component="form"
                          noValidate
                          onSubmit={handleSubmit}
                          sx={{ mt: 2 }}
                        >
                          <Grid container spacing={1}>
                            <Grid item xs={12} sx={{ ml: "3em", mr: "3em" }}>
                              <TextField
                                required
                                fullWidth
                                id="username"
                                label="Username"
                                name="username"
                                autoComplete="username"
                              />
                            </Grid>
                            <Grid item xs={12} sx={{ ml: "3em", mr: "3em" }}>
                              <TextField
                                required
                                fullWidth
                                name="password"
                                label="Password"
                                type="password"
                                id="password"
                                autoComplete="new-password"
                              />
                            </Grid>
                          
                            <Grid item xs={12} sx={{ ml: "5em", mr: "5em" }}>
                              <Button
                                type="submit"
                                variant="contained"
                                fullWidth="true"
                                size="large"
                                sx={{
                                  mt: "10px",
                                  mr: "20px",
                                  borderRadius: 28,
                                  color: "#ffffff",
                                  minWidth: "170px",
                                  backgroundColor: "#FF9A01",
                                }}
                              >
                                Đăng nhập
                              </Button>
                            </Grid>
                            <Grid item xs={12} sx={{ ml: "3em", mr: "3em" }}>
                              <Stack direction="row" spacing={2}>
                                <Typography
                                  variant="body1"
                                  component="span"
                                  style={{ marginTop: "10px" }}
                                >
                                  Bạn chưa có tài khoản?{" "}
                                  <span
                                    style={{ color: "#beb4fb", cursor: "pointer" }}
                                    onClick={() => {
                                      navigate("/register");
                                    }}
                                  >
                                    Tạo tài khoản
                                  </span>
                                </Typography>
                              </Stack>
                            </Grid>
                          </Grid>
                        </Box>
                      </Container>
                    </ThemeProvider>
                  </Box>
                </Grid>
              </Grid>
            </Box>
          </div>
        </>
      );
    
};
    

export default Login;