import "./Mods.scss";
import Button from '@mui/material/Button';
import TextField from '@mui/material/TextField';
import Dialog from '@mui/material/Dialog';
import DialogActions from '@mui/material/DialogActions';
import DialogContent from '@mui/material/DialogContent';
import DialogTitle from '@mui/material/DialogTitle';
import { useEffect, useState } from "react";
import axios from "axios";
import { AuthContext } from '../../contexts/AuthContext.js';
import { useContext } from 'react';
import Pagination from '@mui/material/Pagination';
import {localUrl} from '../../utils/path.js'
import { Table, TableBody, TableCell, TableContainer, TableHead, TableRow, Paper } from '@mui/material';
import { AiOutlineCheck } from "react-icons/ai";
import { IoCloseCircleSharp } from "react-icons/io5";
import Footer from '../../components/Footer'
import LockOutlinedIcon from '@mui/icons-material/LockOutlined';
import LockOpenOutlinedIcon from '@mui/icons-material/LockOpenOutlined';
import CheckIcon from '@mui/icons-material/Check';
import BlockIcon from '@mui/icons-material/Block';
import SearchOutlinedIcon from '@mui/icons-material/SearchOutlined';
import AddCircleOutlineOutlinedIcon from '@mui/icons-material/AddCircleOutlineOutlined';

const Mods = () => {
  const {token, dispatch} = useContext(AuthContext);
  const [mods, setMods] = useState([]);
  const [page, setPage] = useState(1);
  const [lastPage, setLastPage] = useState(null);
  const [open, setOpen] = useState(false);
  const [fullname, setFullname] = useState("");
  const [userName, setUserName ]= useState("");
  const [password, setPassword] = useState("");
  const [inputValue, setInputValue] = useState('');
  const [searchValue, setSearchValue] = useState('');

  const handleInputChange = (event) => {
    setInputValue(event.target.value);
  };

  const handleSearch = () => {
    setSearchValue(inputValue);
    setInputValue('');
  };


  const handleClickOpen = () => {
    setOpen(true);
  };

  const handleClose = () => {
    setOpen(false);
    setFullname("");
    setUserName("");
    setPassword("");
  };


  const handleChange = (event, value) => {
    setPage(value);
  };
  
  const getMods = async () => {
    try {
      const res = await axios.get(`${localUrl}/mods?count_per_page=5&page=${page}&username=${searchValue}`, 
      {
        headers: 
        {
          'Content-Type': 'application/json',
          'Authorization': token
        }})
      setMods(res.data.data.mods.data);
      setLastPage(res.data.data.mods.last_page);
    } catch (error) {
      if (error.response.message === "Unauthenticated.")
      {
      alert("Phiên đăng nhập đã hết hạn!")
      dispatch({ type: "LOGOUT" });
      }
      if(error.response.data.data === null && searchValue !== '')
      {
      alert("Không tìm thấy dữ liệu")
      }
    }
  }

  useEffect(() => {
    getMods();
  }, [page,searchValue]);


  const reloadData = () => {
    getMods();
  };


 
  const handleBan = async (id) => {
    try {
      await axios.put(
        `${localUrl}/admin/mod/ban/${id}`,
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
        `${localUrl}/admin/mod/unban/${id}`,
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
        `${localUrl}/admin/mod/lock/${id}`,
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
        `${localUrl}/admin/mod/unlock/${id}`,
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
      width: 100,
    },
    {
      field: "full_name",
      headerName: "Họ tên",
      width: 100,
    },
    {
      field: "is_banned",
      headerName: "Trạng thái kích hoạt",
      width: 100,
    },
    {
      field: "locked_until",
      headerName: "Thời gian khoá",
      width: 100,
    },
    {
      field: "last_login",
      headerName: "Lần cuối đăng nhập",
      width: 100,
    },
  ];


  const handleSubmit = async (e) => {
    e.preventDefault(); 
    try {
      await axios.post(
        `${localUrl}/admin/mod`,
        {
          "full_name" : fullname,
          "password" : password,
          "username" : userName
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
      if (err.response.data.message === "Unauthenticated.")
      {
      alert("Phiên đăng nhập đã hết hạn!")
      dispatch({ type: "LOGOUT" });
      }
      else alert (err.response.data.message)
    }
  };

  return (

    <div className="Userdatatable">
      <div className="UserdatatableTitle">Tài khoản điều hành viên
      <Button title="Thêm mới" onClick={handleClickOpen}>
      <AddCircleOutlineOutlinedIcon style={{ fontSize: 50 }}/>
    </Button>
    <Dialog open={open} onClose={handleClose}>
      <DialogTitle>Điều hành viên</DialogTitle>
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
          value={fullname}
          onChange={(e) => setFullname(e.target.value)}
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
      />
      <Button 
        variant="contained" 
        onClick={() => handleSearch()}
      >
      <SearchOutlinedIcon/>
      </Button>
      {mods && (
        <TableContainer component={Paper}>
          <Table>
            <TableHead>
              <TableRow>
                {Columns.map((column) => (
                  <TableCell key={column.field}>{column.headerName}</TableCell>
                ))}
                <TableCell>Action</TableCell>
              </TableRow>
            </TableHead>
            <TableBody>
              {mods.map((row) => (
                <TableRow key={row.id}>
                  
                  {Columns.map((column) => (
                    <TableCell key={column.field}>
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
    <Footer/>
  </div>
  );
};

export default Mods;