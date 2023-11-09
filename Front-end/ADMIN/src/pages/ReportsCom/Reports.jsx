import "./Reports.scss";
import { useEffect, useState } from "react";
import axios from "axios";
import { AuthContext } from '../../contexts/AuthContext.js';
import { useContext } from 'react';
import { Table, TableBody, TableCell, TableContainer, TableHead, TableRow, Paper } from '@mui/material';
import {localUrl, userUrl} from '../../utils/path.js'
import Footer from '../../components/Footer'
import Pagination from '@mui/material/Pagination';
import Button from '@mui/material/Button';
import DeleteOutlineIcon from '@mui/icons-material/DeleteOutline';


const Reports = () => {
  const {token, dispatch} = useContext(AuthContext);
  const [comReport, setComReport] = useState([]);
  const [page, setPage] = useState(1);
  const [lastPage, setLastPage] = useState(null);
  
  const handleChange = (event, value) => {
    setPage(value);
  };

  const getReports = async () => {
    try {
      const res = await axios.get(`${localUrl}/company-reports?count_per_page=5&page=${page}`, 
      {
        headers: 
        {
          'Content-Type': 'application/json',
          'Authorization': token
        }
      })
      setComReport(res.data.data.company_reports.data);
      setLastPage(res.data.data.company_reports.last_page);
    } catch (err) {
      if (err.message === "Unauthenticated.")
      {
      alert("Phiên đăng nhập đã hết hạn!")
      dispatch({ type: "LOGOUT" });
      }
    }
  }


  useEffect(() => {
    getReports();
  }, [page]);


  const reloadData = () => {
    getReports();
  };


  
  const Columns = [
    {
      field: "id",
      headerName: "Mã báo cáo",
      width: 100,
    },
    {
      field: "username",
      headerName: "Người báo cáo",
      width: 100,
    },
    {
      field: "name",
      headerName: "Công ty bị báo cáo",
      width: 100,
    },
    {
      field: "reason",
      headerName: "Lý do báo cáo",
      width: 100,
    },
    {
      field: "created_at",
      headerName: "Ngày tạo",
      width: 100,
    },
  ];

  const handleDelete = async (id) => {
    try {
      const isConfirmed = window.confirm('Bạn có chắc chắn muốn xóa báo cáo này?');
  
      if (isConfirmed) {
        await axios.delete(`${localUrl}/company-reports/${id}`, {
          headers: {
            'Content-Type': 'application/json',
            'Authorization': token,
          },
        });
        reloadData();
      }
    } catch (err) {
      if (err.message === 'Unauthenticated.') {
        alert('Phiên đăng nhập đã hết hạn!');
        dispatch({ type: 'LOGOUT' });
      }
    }
  };
  


  return (
    <div className="Userdatatable">
      <div className="UserdatatableTitle">Báo cáo công ty</div>
    {comReport && (
      <TableContainer component={Paper}>
        <Table>
          <TableHead>
            <TableRow>
              {/* Render table headers based on your 'columns' data */}
              {Columns.map((column) => (
                <TableCell key={column.field}>{column.headerName}</TableCell>
              ))}
            </TableRow>
          </TableHead>
          <TableBody>
            {comReport.map((row) => (
              <TableRow key={row.id}>
                <TableCell>{row.id}</TableCell> 
                <TableCell>{row.user.username}</TableCell> 
                <TableCell>
                  <a href={`${userUrl}/employer/${row.company_profile.id}`} target="_blank" rel="noopener noreferrer" style={{ color: 'blue' }}>
                    {row.company_profile.name}
                  </a>
                </TableCell> 
                <TableCell>{row.reason}</TableCell> 
                <TableCell>{row.created_at}</TableCell>
                <TableCell>
                <div className="cellAction">
                  <Button 
                    title ="Xóa báo cáo"
                    variant="outlined" 
                    onClick={() => handleDelete(row.id)}
                    style={{ color: "red", backgroundColor: "white", borderColor: "red" }}
                  >
                    <DeleteOutlineIcon/>
                  </Button>
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

export default Reports;