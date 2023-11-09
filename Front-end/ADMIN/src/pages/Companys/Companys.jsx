import "./Companys.scss";
import { useEffect, useState } from "react";
import axios from "axios";
import { AuthContext } from '../../contexts/AuthContext.js';
import { useContext } from 'react';
import Footer from '../../components/Footer'
import Pagination from '@mui/material/Pagination';
import {localUrl} from '../../utils/path.js'
import { Table, TableBody, TableCell, TableContainer, TableHead, TableRow, Paper } from '@mui/material';
import { AiOutlineCheck } from "react-icons/ai";
import { IoCloseCircleSharp } from "react-icons/io5";
import Button from '@mui/material/Button';
import Views from './ViewModal'
import LockOutlinedIcon from '@mui/icons-material/LockOutlined';
import LockOpenOutlinedIcon from '@mui/icons-material/LockOpenOutlined';
import CheckIcon from '@mui/icons-material/Check';
import BlockIcon from '@mui/icons-material/Block';
import SearchOutlinedIcon from '@mui/icons-material/SearchOutlined';


const Companys = ( ) => {
  const {token, dispatch} = useContext(AuthContext);
  const [companys, setCompanys] = useState([]);
  const [page, setPage] = useState(1);
  const [lastPage, setLastPage] = useState(null);
  const [inputValue, setInputValue] = useState('');
  const [searchValue, setSearchValue] = useState('');

  const handleChange = (event, value) => {
    setPage(value);
  };

  const getCompanys = async () => {
    try {
      const res = await axios.get(`${localUrl}/company-accounts?count_per_page=5&page=${page}&username=${searchValue}`, 
      {
        headers: 
        {
          'Content-Type': 'application/json',
          'Authorization': token
        }
      })
      setCompanys(res.data.data.company_accounts.data);
      setLastPage(res.data.data.company_accounts.last_page);
    } catch (err) {
      if (err.response.message === "Unauthenticated.")
      {
      alert("Phiên đăng nhập đã hết hạn!")
      dispatch({ type: "LOGOUT" });
      }
      if(err.response.data.data === null)
      {
      alert("Không tìm thấy dữ liệu")
      }
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
    getCompanys();
  }, [page,searchValue]);


  const reloadData = () => {
    getCompanys();
  };

  const handleBan = async (id) => {
    try {
      await axios.put(
        `${localUrl}/company-accounts/ban/${id}`,
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
        `${localUrl}/company-accounts/unban/${id}`,
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
        `${localUrl}/company-accounts/lock/${id}`,
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
        `${localUrl}/company-accounts/unlock/${id}`,
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
      field: "is_banned",
      headerName: "Trạng thái kích hoạt",
      width: 100,
    },
    {
      field: "locked_until",
      headerName: "Thời gian khoá	",
      width: 100,
    },
    {
      field: "last_login",
      headerName: "Lần cuối đăng nhập",
      width: 100,
    },
  ];
  

  return (
    <div className="Userdatatable">
      <div className="UserdatatableTitle">Tài khoản công ty</div>
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
      {companys && (
        <TableContainer component={Paper}>
          <Table>
            <TableHead>
              <TableRow>
                {/* Render table headers based on your 'columns' data */}
                {Columns.map((column) => (
                  <TableCell key={column.field}>{column.headerName}</TableCell>
                ))}
                <TableCell>Action</TableCell>
              </TableRow>
            </TableHead>
            <TableBody>
              {/* Render table rows based on 'gridData' */}
              {companys.map((row) => (
                <TableRow key={row.id}>
                  {/* Render table cells based on 'columns' data */}
                  {Columns.map((column) => (
                    <TableCell key={column.field}>
                    {/* Conditionally render cell value */}
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
                  {/* Render custom action cell */}
                  <TableCell>
                  <div className="cellAction">

                <Views id={row.id}/>

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


export default Companys;