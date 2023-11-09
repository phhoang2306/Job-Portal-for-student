import { useState, useEffect } from "react";
import { localUrl,userUrl } from "../../utils/path";
import axios from "axios";
import { AuthContext } from '../../contexts/AuthContext';
import { useContext } from 'react';
import Pagination from '@mui/material/Pagination';
import Button from '@mui/material/Button';
import DeleteOutlineIcon from '@mui/icons-material/DeleteOutline';
import { Table, TableBody, TableCell, TableContainer, TableHead, TableRow, Paper,Typography } from '@mui/material';
import BlockIcon from '@mui/icons-material/Block';
import moment from 'moment';
import Timetable from './components/Timetable'


const Application = () => {
  const {user, token , dispatch} = useContext(AuthContext);
  const [applies, setApplies] = useState([]);
  const [loading, setLoading] = useState(true);
  const [page, setPage] = useState(1);
  const [lastPage, setLastPage] = useState(1);


  const handleChange = (event, value) => {
    setPage(value);
  };

  

  const fetchApplications = async () => {
    try {
      const res = await axios.get(`${localUrl}/applications?company_id=${user.id}`,
      {
        headers: {
          'Content-Type': 'application/json',
          'Authorization': token
        }
      }
      );
      console.log(res.data.data.applications)
        setApplies(res.data.data.applications);
        setLastPage(res.data.data.applications.last_page);
    } catch (error) {
      ////conso.log(error);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchApplications();
  }, [page]);

  const reloadData = () => {
    fetchApplications();
  };


  if (loading) {
    return <div>Đang tải dữ liệu...</div>;
  }

  const Columns = [
    {
      field: "avatar",
      headerName: "Avatar",
      width: 100,
    },
    {
      field: "full_name",
      headerName: "Tên ứng viên",
      width: 'auto',
    },
    {
      field: "title",
      headerName: "Công việc ứng tuyển",
      width: 'auto',
    },
    {
      field: "status",
      headerName: "Trạng thái",
      width: 'auto',
    },
    {
      field: "created_at",
      headerName: "Ngày gửi",
      width: 'auto',
    }
  ];


  const handleReject = async (id) => {
    const url = `${localUrl}/applications/reject/${id}`;
    const headers = {
      Accept: "application/json",
      Authorization: token,
    };
    // ask for confirmation
    const confirmation = confirm("Bạn có chắc chắn muốn từ chối đơn ứng tuyển này?");
    if (confirmation) {
      try {
        const res = await fetch(url, { method: "PUT", headers });
        if (!res.error) {
          const data = await res.json();
          if (!data.error) {
            alert("Đã từ chối");
            reloadData();
          } else {
            alert("Đã có lỗi xảy ra, vui lòng thử lại sau");
          }
        }
      } catch (error) {
      }
    }
  };

  const handleViewCV = (cv_path) => {
    window.open(cv_path, '_blank');
  };

  

  return (
    <div className="tabs-box">
    <Typography sx={{mb: 2}} variant="h5">Danh sách đơn ứng tuyển</Typography>
    {/* End filter top bar */}

    {/* Start table widget content */}
    {applies && (
      <TableContainer component={Paper}>
        <Table>
          <TableHead>
            <TableRow>
              {/* Render table headers based on your 'columns' data */}
              {Columns.map((column) => (
                <TableCell key={column.field} sx={{width: column.width}}>{column.headerName}</TableCell>
              ))}
              <TableCell>Hành động</TableCell>
            </TableRow>
          </TableHead>
          <TableBody>
          {applies && applies.data && applies.data.length > 0 ? (
              // Đoạn mã hiển thị dữ liệu nếu applies có dữ liệu
              applies.data.map((row) => (
                <TableRow key={row.id}>
                  <TableCell>
                    <img src={row.user_profile.avatar} alt="Avatar" />
                  </TableCell>
                  <TableCell>{row.user_profile.full_name}</TableCell> 
                  <TableCell>
                    <a href={`${userUrl}/job/${row.job.id}`} target="_blank" rel="noopener noreferrer">
                      {row.job.title}
                    </a>
                  </TableCell>
                  <TableCell>{row.status}</TableCell> 
                  <TableCell>{moment(row.created_at).format('DD-MM-YYYY')}</TableCell>
                  <TableCell> 
                    <div className="cellAction">
                      <Timetable timetable={row.time_table} />
                      <Button 
                        title="Xem CV ứng viên"
                        variant="outlined" 
                        style={{ color: "green", backgroundColor: "white", borderColor: "green" }}
                        onClick={() => handleViewCV(row.cv.cv_path)}
                      >
                        CV
                      </Button>
                      <Button 
                        title ="Từ chối"
                        variant="outlined" 
                        onClick={() => handleReject(row.id)}
                        style={{ color: "red", backgroundColor: "white", borderColor: "red" }}
                      >
                        <BlockIcon/>
                      </Button>
                    </div>
                  </TableCell>
                </TableRow>
              ))
            ) : (
              // Hiển thị "Không có đơn ứng tuyển" nếu applies là một mảng rỗng
              <TableRow>
                <TableCell colSpan={6}>Không có đơn ứng tuyển</TableCell>
              </TableRow>
            )}
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

export default Application;
