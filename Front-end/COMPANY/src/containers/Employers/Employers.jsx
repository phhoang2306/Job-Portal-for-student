import "./Employers.scss";
import { useEffect, useState } from "react";
import axios from "axios";
import { AuthContext } from '../../contexts/AuthContext';
import { useContext } from 'react';
import { Table, TableBody, TableCell, TableContainer, TableHead, TableRow, Paper } from '@mui/material';
import { AiOutlineCheck } from "react-icons/ai";
import { IoCloseCircleSharp } from "react-icons/io5";
import {localUrl} from '../../utils/path'
import Pagination from '@mui/material/Pagination';
import Button from '@mui/material/Button';
import LockOutlinedIcon from '@mui/icons-material/LockOutlined';
import LockOpenOutlinedIcon from '@mui/icons-material/LockOpenOutlined';
import CheckIcon from '@mui/icons-material/Check';
import BlockIcon from '@mui/icons-material/Block';
import SearchOutlinedIcon from '@mui/icons-material/SearchOutlined';
import TextField from '@mui/material/TextField';
import Dialog from '@mui/material/Dialog';
import DialogActions from '@mui/material/DialogActions';
import DialogContent from '@mui/material/DialogContent';
import DialogTitle from '@mui/material/DialogTitle';
import AddCircleOutlineOutlinedIcon from '@mui/icons-material/AddCircleOutlineOutlined';


const Employers = () => {
  const {user, token , dispatch} = useContext(AuthContext);
  const [employers, setEmployers] = useState([]);
  const [page, setPage] = useState(1);
  const [lastPage, setLastPage] = useState(null);
  const [inputValue, setInputValue] = useState('');
  const [searchValue, setSearchValue] = useState('');
  const [open, setOpen] = useState(false);
  const [userName, setUserName] = useState('');
  const [fullName, setFullName] = useState('');
  const [password, setPassword] = useState('');
  const [comPass, setComPass] = useState('');  

  const handleClickOpen = () => {
    setOpen(true);
  };

  const handleClose = () => {
    setOpen(false);
    setFullname("");
    setUserName("");
    setPassword("");
    setComPass("");
    setAvatar("");
  };


  const handleChange = (event, value) => {
    setPage(value);
  };

  const getEmployers = async () => {
    try {
      const res = await axios.get(`${localUrl}/employer-accounts?company_id=${user.id}&count_per_page=5&page=${page}&username=${searchValue}`, 
      {
        headers: 
        {
          'Content-Type': 'application/json',
          'Authorization': token
        }
      })
      setEmployers(res.data.data.employer_accounts.data);
      setLastPage(res.data.data.employer_accounts.last_page);
    } catch (err) {
      if (err.response.message === "Unauthenticated.")
      {
      alert("Phiên đăng nhập đã hết hạn!")
      dispatch({ type: "LOGOUT" });
      }
      if(err.response.data.data === null && searchValue !== '')
      {
      alert("Không tìm thấy dữ liệu")
      }
      //conso.log(err)
    }
  }

  const handleInputChange = (event) => {
    setInputValue(event.target.value);
  };

  const handleSearch = () => {
    setSearchValue(inputValue);
    setInputValue('');
  };


  useEffect(() => {
    getEmployers();
  }, [page, searchValue]);

  const reloadData = () => {
    getEmployers();
  };


  const handleBan = async (id) => {
    try {
      await axios.put(
        `${localUrl}/employer-accounts/ban/${id}`,
        {},
        {
          headers: {
            'Content-Type': 'application/json',
            'Authorization': token
          },
        }
      );
      reloadData()
    } catch (err) {
      if (err.message === "Unauthenticated.")
      {
      alert("Phiên đăng nhập đã hết hạn!")
      dispatch({ type: "LOGOUT" });
      }
    }
  };

  const handleUnban = async (id) => {
    try {
      await axios.put(
        `${localUrl}/employer-accounts/unban/${id}`,
        {},
        {
          headers: {
            'Content-Type': 'application/json',
            'Authorization': token
          },
        }
      );
      reloadData()
    } catch (err) {
      if (err.message === "Unauthenticated.")
      {
      alert("Phiên đăng nhập đã hết hạn!")
      dispatch({ type: "LOGOUT" });
      }
    }
  };

  const handleLock = async (id) => {
    const currentDate = new Date();

    // Get the month and year of the current date
    let currentMonth = currentDate.getMonth();
    let currentYear = currentDate.getFullYear();
    let curDate = currentDate.getDate();

    let nextMonth = currentMonth + 1;
    let nextYear = currentYear;
 
    if (nextMonth > 12) {
      nextMonth = 0;
      nextYear += 1;
    }

    const nextDate = new Date(nextYear, nextMonth, 28);
  
    // Format the dates as "dd/mm/yyyy"
    const formattedNextDate = nextDate.toISOString().slice(0, 10);
    try {
      await axios.put(
        `${localUrl}/employer-accounts/lock/${id}`,
        {
          "locked_until": formattedNextDate,
      },
        {
          headers: {
            'Content-Type': 'application/json',
            'Authorization': token
          },
        }
      );
      reloadData()
    } catch (err) {
      if (err.message === "Unauthenticated.")
      {
      alert("Phiên đăng nhập đã hết hạn!")
      dispatch({ type: "LOGOUT" });
      }
    }
  };

  const handleUnlock = async (id) => {
    try {
      await axios.put(
        `${localUrl}/employer-accounts/unlock/${id}`,
        {},
        {
          headers: {
            'Content-Type': 'application/json',
            'Authorization': token
          },
        }
      );
      reloadData()
    } catch (err) {
      if (err.message === "Unauthenticated.")
      {
      alert("Phiên đăng nhập đã hết hạn!")
      dispatch({ type: "LOGOUT" });
      }
    }
  };
  
  const Columns = [
    {
      field: "username",
      headerName: "Tên đăng nhập",
      width: 'auto',
    },
    {
      field: "is_banned",
      headerName: "Trạng thái kích hoạt",
      width: 'auto',
    },
    {
      field: "locked_until",
      headerName: "Thời gian khoá",
      width: 'auto',
    },
    {
      field: "last_login",
      headerName: "Lần cuối đăng nhập",
      width: 'auto',
    },
  ];

  const handleSubmit = async (e) => {
    e.preventDefault(); 
    try {
      await axios.post(
        `${localUrl}/employer-accounts`,
        {
          "full_name" : fullName,
          "password" : password,
          "confirm_password" : comPass,
          "username" : userName,
        },
        {
          headers: {
            'Content-Type': 'application/json',
            'Authorization': token
          },
        }
      );
      reloadData()
      handleClose()
    } catch (err) {
      ////conso.log(err)
    }
  };


  return (
    <div className="Userdatatable">
      <div className="UserdatatableTitle">Tài khoản nhân viên
      <Button title="Thêm mới" onClick={handleClickOpen}>
      <AddCircleOutlineOutlinedIcon style={{ fontSize: 50 }}/>
    </Button>
    <Dialog open={open} onClose={handleClose}>
      <DialogTitle>Nhân viên</DialogTitle>
      <form onSubmit={handleSubmit}>
      <DialogContent>

        <TextField
          autoFocus
          margin="dense"
          id="fullname"
          label="Họ và tên"
          type="text"
          fullWidth
          variant="standard"
          required
          value={fullName}
          onChange={(e) => setFullName(e.target.value)}
        />

        <TextField
          autoFocus
          margin="dense"
          id="name"
          label="Tên đăng nhập (phải có trên 8 ký tự)"
          type="text"
          fullWidth
          variant="standard"
          required
          value={userName}
          onChange={(e) => setUserName(e.target.value)}
        />

        <TextField
          autoFocus
          margin="dense"
          id="password"
          label="Mật khẩu (Phải có 8 kí tự, bao gồm chữ In hoa và số)"
          type="password"
          fullWidth
          variant="standard"
          required
          value={password}
          onChange={(e) => setPassword(e.target.value)}
        />

        <TextField
          autoFocus
          margin="dense"
          id="ComPass"
          label="Nhập lại mật khẩu"
          type="password"
          fullWidth
          variant="standard"
          required
          value={comPass}
          onChange={(e) => setComPass(e.target.value)}
        />
      </DialogContent>
      <DialogActions>
        <Button onClick={handleClose}>Hủy</Button>
        <Button type="submit">Tạo</Button>
      </DialogActions>
      </form>
    </Dialog>

    </div>
      <input
      type="text"
      placeholder="Tên đăng nhập"
      value={inputValue}
      onChange={handleInputChange}
      style={{
        border: '2px solid #000', 
        borderRadius: '4px',     
        padding: '8px',          
      }}
    />
    <Button 
      variant="contained" 
      onClick={() => handleSearch()}
    >
    <SearchOutlinedIcon/>
    </Button>
    
      {employers && (
        <TableContainer component={Paper}>
          <Table>
            <TableHead>
              <TableRow>
              <TableCell sx={{width: 'auto',  textAlign: 'center'}} >Họ tên</TableCell>
                {Columns.map((column) => (
                  <TableCell key={column.field} sx={{width: column.width,  textAlign: column.textAlign}} >{column.headerName}</TableCell>
                ))}
                <TableCell>Action</TableCell>
              </TableRow>
            </TableHead>
            <TableBody>
              {employers.map((row) => (
              <TableRow key={row.id}>
              
              <TableCell sx={{textAlign: 'center'}}>
                {row.profile.full_name}
              </TableCell>
              {Columns.map((column) => (
                <TableCell key={column.field} >
                {column.field === "is_banned" ? (
                  row[column.field] === 0 ? (
                    <AiOutlineCheck style={{ color: "green", fontSize: 20 }} />
                  ) : (
                    <IoCloseCircleSharp style={{ color: "red", fontSize: 20 }} />
                  )
                ) : (
                  row[column.field] === null ? "Chờ cập nhật" : row[column.field]
                )}
              </TableCell>
                  ))}
                  <TableCell>
                    <div className="cellAction">


                      {row.locked_until !== null ? (
                        <Button 
                          title="Mở khóa tài khoản"
                          variant="outlined" 
                          onClick={() => handleUnlock(row.id)}
                          style={{ color: "green", backgroundColor: "white", borderColor: "green" }}
                        >
                          <LockOpenOutlinedIcon/>
                        </Button>
                      ) : (
                        <Button 
                          title="Khóa tài khoản"
                          variant="outlined" 
                          onClick={() => handleLock(row.id)}
                          style={{ color: "green", backgroundColor: "white", borderColor: "green" }}
                        >
                          <LockOutlinedIcon/>
                        </Button>
                      )}
                      {row.is_banned === 1 ? (
                        <Button 
                        title="Kích hoạt tài khoản"
                        variant="outlined" 
                        onClick={() => handleUnban(row.id)}
                        style={{ color: "red", backgroundColor: "white", borderColor: "red" }}
                        >
                        <CheckIcon/>
                        </Button>
                      ) : (
                        <Button 
                          title="Hủy kích hoạt"
                          variant="outlined" 
                          onClick={() => handleBan(row.id)}
                          style={{ color: "red", backgroundColor: "white", borderColor: "red" }}
                        >
                          <BlockIcon/>
                        </Button>
                      )}
                    </div>
                  </TableCell>
                </TableRow>
              ))}
            </TableBody>
          </Table>
        </TableContainer>
      )}
      <div className="pagination-container mt-10">
        <Pagination
          className="pagination"
          count={lastPage}
          page={page}
          onChange={handleChange}
        />
      </div>      
    </div>
  );
};

export default Employers;