import Box from "@mui/material/Box";
import Grid from "@mui/material/Grid";
import bg from "../bg/signin.svg";
import bgimg from "../bg/backimg.jpg";
import Button from "@mui/material/Button";
import TextField from "@mui/material/TextField";
import Typography from "@mui/material/Typography";
import Container from "@mui/material/Container";
import Avatar from "@mui/material/Avatar";
import LockOutlinedIcon from "@mui/icons-material/LockOutlined";
import { ThemeProvider, createTheme } from "@mui/material/styles";
import Checkbox from "@mui/material/Checkbox";
import FormControlLabel from "@mui/material/FormControlLabel";
import { useState, forwardRef } from "react";
import Stack from "@mui/material/Stack";
import Slide from "@mui/material/Slide";
import { useNavigate } from "react-router-dom";
import axios from "axios"
import {localUrl} from "../../utils/path"
import Select from '@mui/material/Select';
import InputLabel from '@mui/material/InputLabel';
import MenuItem from '@mui/material/MenuItem';



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
  left: "30%",
};

const left = {
  position: "relative",
  top: "50%",
};

export default function Register() {
  const navigate = useNavigate();


  const handleSubmit = async (event) => {
    event.preventDefault();
    const data = new FormData(event.currentTarget);
    if (data.get("password") === data.get("confirmpassword")) {
      try {
        await axios.post( `${localUrl}/auth-company/sign-up`,
          {
            "password" : data.get("password"),
            "username" : data.get("username"),
            "confirm_password" : data.get("confirmpassword"),
            "name": data.get("name"),
            "email": data.get("email"),
            "site":data.get("site"),
            "phone": data.get("phone"),
            "address":data.get("address"),
            "description": data.get("description"),
            "size": data.get("size"),
          },
          {
            headers: {
              'Accept': 'application/json',
            },
          }
        );
        alert("Bạn đã đăng ký thành công!");
        navigate("/login")
      } catch (error) {
        //conso.log(error)
        alert(error.response.data.message);
      }
    }
    else 
    {
      alert("Xác nhận mật khẩu nhập không chính xác")
    }
  };

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
                  height: "100vh",
                  minHeight: "1100px",
                  backgroundColor: "#3b33d5",
                }}
              >
                <ThemeProvider theme={darkTheme}>
                  <Container>
                    <Box height={35} />
                    <Box sx={left}>
                      <Typography component="h1" variant="h4">
                        Tạo tài khoản tuyển dụng FinDev
                      </Typography>
                    </Box>
                    <Box
                      component="form"
                      noValidate
                      onSubmit={handleSubmit}
                      sx={{ mt: 2 }}
                    >
                      <Grid container spacing={1}>
                      <label style={{marginTop: 20}}>Thông tin tài khoản</label>
                        <Grid item xs={12} sx={{ ml: "3em", mr: "3em" }}>
                          <TextField
                            required
                            fullWidth
                            id="username"
                            label="Tên tài khoản"
                            name="username"
                            autoComplete="username"
                          />
                        </Grid>
                        <Grid item xs={12} sx={{ ml: "3em", mr: "3em" }}>
                          <TextField
                            required
                            fullWidth
                            name="password"
                            label="Mật khẩu"
                            type="password"
                            id="password"
                            autoComplete="new-password"
                          />
                        </Grid>
                        <Grid item xs={12} sx={{ ml: "3em", mr: "3em" }}>
                          <TextField
                            required
                            fullWidth
                            name="confirmpassword"
                            label="Xác nhận mật khẩu"
                            type="password"
                            id="confirmpassword"
                            autoComplete="new-password"
                          />
                        </Grid>


                        <label style={{marginTop: 20}}>Thông tin công ty</label>
                        <Grid item xs={12} sx={{ ml: "3em", mr: "3em" }}>
                          <TextField
                            required
                            fullWidth
                            id="name"
                            label="Tên công ty"
                            name="name"
                            autoComplete="name"
                          />
                        </Grid>

                        <Grid item xs={12} sx={{ ml: "3em", mr: "3em" }}>
                        <TextField
                          required
                          fullWidth
                          id="phone"
                          label="Số điện thoại"
                          name="phone"
                          autoComplete="phone"
                        />
                      </Grid>

                        <Grid item xs={12} sx={{ ml: "3em", mr: "3em" }}>
                          <TextField
                            required
                            fullWidth
                            id="email"
                            label="Email của công ty"
                            name="email"
                            autoComplete="email"
                          />
                        </Grid>

                        <Grid item xs={12} sx={{ ml: "3em", mr: "3em" }}>
                        <TextField
                          required
                          fullWidth
                          id="address"
                          label="Địa chỉ của công ty"
                          name="address"
                          autoComplete="address"
                        />
                      </Grid>

                        <Grid item xs={12} sx={{ ml: "3em", mr: "3em" }}>
                          <TextField
                            required
                            fullWidth
                            type="number"
                            id="size"
                            label="Số lượng nhân viên"
                            name="size"
                            autoComplete="size"
                            inputProps={{ min: '0' }}
                          />
                        </Grid>

                        <Grid item xs={12} sx={{ ml: "3em", mr: "3em" }}>
                        <TextField
                            required
                            fullWidth
                            multiline
                            rows={5}
                            id="description"
                            label="Mô tả về công ty"
                            name="description"
                            autoComplete="description"
                          />
                      </Grid>

                      <Grid item xs={12} sx={{ ml: "3em", mr: "3em" }}>
                        <TextField
                          fullWidth
                          id="site"
                          label="Đường dẫn Website công ty (nếu có)"
                          name="site"
                          autoComplete="site"
                        />
                        </Grid>
          
                        <Grid item xs={12} sx={{ ml: "5em", mr: "5em" }}>
                          <Button
                            type="submit"
                            variant="contained"
                            fullWidth="true"
                            size="large"
                            sx={{
                              mt: "15px",
                              mr: "20px",
                              borderRadius: 28,
                              color: "#ffffff",
                              minWidth: "170px",
                              backgroundColor: "#FF9A01",
                            }}
                          >
                            Đăng ký
                          </Button>
                        </Grid>
                        <Grid item xs={12} sx={{ ml: "3em", mr: "3em" }}>
                          <Stack direction="row" spacing={2}>
                            <Typography
                              variant="body1"
                              component="span"
                              style={{ marginTop: "10px" }}
                            >
                              Bạn đã có tài khoản?{" "}
                              <span
                                style={{ color: "#beb4fb", cursor: "pointer" }}
                                onClick={() => {
                                    navigate("/login");
                                  }}
                              >
                                Đăng nhập
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
}
