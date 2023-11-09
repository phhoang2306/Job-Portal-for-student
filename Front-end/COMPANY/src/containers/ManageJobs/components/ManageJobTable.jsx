import { useState, useEffect } from "react";
import { localUrl, userUrl } from "../../../utils/path";
import axios from "axios";
import { AuthContext } from '../../../contexts/AuthContext';
import { useContext } from 'react';
import Pagination from '@mui/material/Pagination';
import Button from '@mui/material/Button';
import DeleteOutlineIcon from '@mui/icons-material/DeleteOutline';
import { Table, TableBody, TableCell, TableContainer, TableHead, TableRow, Paper } from '@mui/material';
import RecommendModal from './RecommendModal/RecommendModal'
import EditModal from './EditModal/EditModal'

import CancelIcon from '@mui/icons-material/Cancel';

const ManageJobTable = () => {
  const {user, token , dispatch} = useContext(AuthContext);
  const [jobs, setJobs] = useState([]);
  const [loading, setLoading] = useState(true);
  const [page, setPage] = useState(1);
  const [lastPage, setLastPage] = useState(1);
  const handleChange = (event, value) => {
    setPage(value);
  };

  const fetchJobListings = async () => {
    try {
      const res = await axios.get(`${localUrl}/jobs?top=1&company_id=${user.id}&page=${page}`);
      if (!res.error) {
        setJobs(res.data.data.jobs.data);
        setLastPage(res.data.data.jobs.last_page);
      }
    } catch (error) {
      ////conso.log(error)

    } finally {
      setLoading(false);
    }
  };


  useEffect(() => {
    fetchJobListings();
  }, [page]);

  const reloadData = () => {
    fetchJobListings();
    setPage(1)
  };

  if (loading) {
    return <div>Đang tải dữ liệu...</div>;
  }
  const handleDeleteJob = async (id) => {
    const url = `${localUrl}/jobs/${id}`;
    const headers = {
      Accept: "application/json",
      Authorization: token,
    };
    // ask for confirmation
    const confirmation = confirm("Bạn có chắc chắn muốn xóa?");
    if (confirmation) {
      try {
        const res = await fetch(url, { method: "DELETE", headers });
        if (!res.error) {
          const data = await res.json();
          if (!data.error) {
            alert("Xóa thành công");
            reloadData();
          } else {
            alert("Đã có lỗi xảy ra, vui lòng thử lại sau");
          }
        }
      } catch (error) {
        if (error.response && error.response.data.message === "Unauthenticated.") {
          alert("Phiên làm việc đã hết hạn, vui lòng đăng nhập lại");

        }
      }
    }
  };

  const handleStopJob = async (id) => {
    const url = `${localUrl}/jobs/stop/${id}`;
    const headers = {
      Accept: "application/json",
      Authorization: token,
    };
    // ask for confirmation
    const confirmation = confirm("Bạn có chắc chắn muốn dừng tuyển công việc này ?");
    if (confirmation) {
      try {
        const res = await fetch(url, { method: "PUT", headers });
        if (!res.error) {
          const data = await res.json();
          if (!data.error) {
            alert("Dừng tuyển công việc thành công");
            reloadData();
          } else {
            alert("Đã có lỗi xảy ra, vui lòng thử lại sau");
          }
        }
      } catch (error) {
        if (error.response && error.response.data.message === "Unauthenticated.") {
          alert("Phiên làm việc đã hết hạn, vui lòng đăng nhập lại");

        }
      }
    }
  };

  const Columns = [
    {
      field: "title",
      headerName: "Tên công việc",
      width: 300,
      textAlign: 'left'
    },
    {
      field: "applications_count",
      headerName: "Đơn ứng tuyển",
      width: 150,
      textAlign: 'center'
    },
    {
      field: "status",
      headerName: "Trạng thái",
      width: 200,
      textAlign: 'center'
    },
    {
      field: "action",
      headerName: "Hành động",
      width: 'auto',
      textAlign: 'left'
    },

  ];

  

  return (
    <div className="tabs-box">
      <div className="widget-title">
        <h4>Danh sách công việc đăng tuyển</h4>
      </div>
      {/* End filter top bar */}

      {/* Start table widget content */}
      {jobs && (
        <TableContainer component={Paper}>
          <Table>
            <TableHead>
              <TableRow>
                {/* Render table headers based on your 'columns' data */}
                {Columns.map((column) => (
                  <TableCell key={column.field} sx={{width: column.width,  textAlign: column.textAlign}}>{column.headerName}</TableCell>
                ))}
              </TableRow>
            </TableHead>
            <TableBody>
              {jobs.map((row) => (
                <TableRow key={row.id}>
                  <TableCell>
                    <a href={`${userUrl}/job/${row.id}`} target="_blank" rel="noopener noreferrer">
                      {row.title}
                    </a>
                  </TableCell>
                  <TableCell sx={{ textAlign: 'center' }}>
                    {row.applications_count === 0 ? "Không có" : `${row.applications_count} đơn`}
                  </TableCell>

                  <TableCell sx={{ textAlign: 'center' }}>
                    {row.status }
                  </TableCell>
                  
                  <TableCell> 
                  <div className="cellAction">
                  <EditModal job_id={row.id} token={token}/>
                  <RecommendModal job_id={row.id} token={token}/>

                    <Button 
                      title ="Dừng tuyển"
                      variant="outlined" 
                      onClick={() => handleStopJob(row.id)}
                      style={{ color: "red", backgroundColor: "white", borderColor: "red" }}
                    >
                      <CancelIcon/>
                    </Button>

                    <Button 
                      title ="Xóa công việc"
                      variant="outlined" 
                      onClick={() => handleDeleteJob(row.id)}
                      style={{ color: "black", backgroundColor: "white", borderColor: "black" }}
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
      {/* End table widget content */}
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

export default ManageJobTable;
