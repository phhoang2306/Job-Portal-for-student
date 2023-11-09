import PropTypes from 'prop-types';
import Button from '@mui/material/Button';
import { styled } from '@mui/material/styles';
import Dialog from '@mui/material/Dialog';
import DialogTitle from '@mui/material/DialogTitle';
import DialogContent from '@mui/material/DialogContent';
import IconButton from '@mui/material/IconButton';
import CloseIcon from '@mui/icons-material/Close';
import Typography from '@mui/material/Typography';
import { useEffect, useState } from "react";
import {recomend} from '../../../../utils/path'
import axios from "axios";
import { Avatar } from '@mui/material';
import VisibilityIcon from '@mui/icons-material/Visibility';
import { Table, TableBody, TableCell, TableContainer, TableHead, TableRow, Paper } from '@mui/material';
import Pagination from '@mui/material/Pagination';
import ReceiptLongIcon from '@mui/icons-material/ReceiptLong';
import Backdrop from '@mui/material/Backdrop';
import CircularProgress from '@mui/material/CircularProgress';
import TextField from '@mui/material/TextField';
import SendNoti from './SendNoti'

const BootstrapDialog = styled(Dialog)(({ theme }) => ({
  '& .MuiDialogContent-root': {
    padding: theme.spacing(2),
  },
  '& .MuiDialogActions-root': {
    padding: theme.spacing(1),
  },
}));

function BootstrapDialogTitle(props) {
  const { children, onClose, ...other } = props;

  return (
    <DialogTitle sx={{ m: 0, p: 2 }} {...other}>
      {children}
      {onClose ? (
        <IconButton
          aria-label="close"
          onClick={onClose}
          sx={{
            position: 'absolute',
            right: 8,
            top: 8,
            color: (theme) => theme.palette.grey[500],
          }}
        >
          <CloseIcon />
        </IconButton>
      ) : null}
    </DialogTitle>
  );
}

BootstrapDialogTitle.propTypes = {
  children: PropTypes.node,
  onClose: PropTypes.func.isRequired,
};

const RecommendModal = ({job_id, token}) => {
  const [open, setOpen] = useState(false);
  const [listApp, setListApp] = useState([]);
  const [message, setMessage] = useState(false);
  const [loading, setLoading] = useState(true);
  const [lastPage, setLastPage] = useState(1);
  const [page, setPage] = useState(1);

  const handleClickOpen = () => {
    setOpen(true);
  };
  const handleClose = () => {
    setOpen(false);
  };

  const handleChange = (event, value) => {
    setPage(value);
  };

  const getList = async () => {
    try {
      const res = await axios.get(`${recomend}/?job_id=${job_id}&page=${page}&limit=10`);
      if(res.data.error === true)
      {
        setMessage(true);
        setLoading(false);
      }
      setListApp(res.data.data.user_profiles.data);
      setLastPage(res.data.data.user_profiles.pagination_info.last_page)
      setLoading(false);
    } catch (err) {
    }
  };

  useEffect(() => {
    getList();
  }, [page]);

  const Columns = [
    {
      field: "avatar",
      headerName: "Avatar",
      width: 100,
    },
    {
      field: "full_name",
      headerName: "Tên ứng viên",
      width: 100,
    },
    {
      field: "gender",
      headerName: "Giới tính",
      width: 100,
    },
    {
      field: "address",
      headerName: "Địa chỉ",
      width: 100,
    },
    {
      field: "phone",
      headerName: "Số điện thoại",
      width: 100,
    },
    {
      field: "email",
      headerName: "Email",
      width: 100,
    },
    {
      field: "action",
      headerName: "Hành động",
      width: 200,
   },
  ];

  const handleViewCV = (cv_path) => {
    window.open(cv_path, '_blank');
  };

  if (loading) {
    return (
      <>
      <Button
        variant="outlined"
        onClick={handleClickOpen}
        title="Xem danh sách ứng viên"
      >
        <ReceiptLongIcon />
      </Button>
      <BootstrapDialog
        onClose={handleClose}
        aria-labelledby="customized-dialog-title"
        open={open}
        sx={{
          '& .MuiDialog-paper': {
            width: '90%',
            maxWidth: 'none',
          },
        }}
      >
      <Backdrop
        sx={{ color: '#fff', zIndex: (theme) => theme.zIndex.drawer + 1 }}
        open={open}
        onClick={handleClose}
      >
        <CircularProgress color="inherit" /> Đang tính toán dữ liệu (có thể mất vài phút)
      </Backdrop>
      </BootstrapDialog>
      </>
    )
  }
return(
  <>
  <Button
    variant="outlined"
    onClick={handleClickOpen}
    title="Xem danh sách ứng viên"
  >
    <ReceiptLongIcon />
  </Button>
  <BootstrapDialog
    onClose={handleClose}
    aria-labelledby="customized-dialog-title"
    open={open}
    sx={{
      '& .MuiDialog-paper': {
        width: '90%',
        maxWidth: 'none',
      },
    }}
  >
    <BootstrapDialogTitle id="customized-dialog-title" onClose={handleClose}>
      Danh sách ứng viên
    </BootstrapDialogTitle>
    <DialogContent dividers>
    {message === true ? (
      <div>Không có ứng viên phù hợp</div>
    ) : (
      <>
        {listApp && (
          <TableContainer component={Paper}>
            <Table>
              <TableHead>
                <TableRow>
                  {Columns.map((column) => (
                    <TableCell key={column.field}>{column.headerName}</TableCell>
                  ))}
                </TableRow>
              </TableHead>
              <TableBody>
                {listApp.map((row) => (
                  <TableRow key={row.id}>
                    <TableCell>
                      <img src={row.avatar} style={{ width: '50px', height: '50px' }} alt="Avatar" />
                    </TableCell>
                    <TableCell>{row.full_name ? row.full_name : 'Không có'}</TableCell>
                    <TableCell>{row.gender ? row.gender : 'Không có'}</TableCell>
                    <TableCell>{row.address ? row.address : 'Không có'}</TableCell>
                    <TableCell>{row.phone ? row.phone : 'Không có'}</TableCell>
                    <TableCell>{row.email ? row.email : 'Không có'}</TableCell>
                    <TableCell> 
                     <div className="cellAction"> 
                     <SendNoti job_id={job_id} token={token} user_id={row.id}/>

                     {row.cv_path ? (
                      <Button
                        title="Xem CV ứng viên"
                        variant="outlined"
                        style={{ color: "green", backgroundColor: "white", borderColor: "green" }}
                        onClick={() => handleViewCV(row.cv_path)}
                      >
                        CV
                      </Button>
                    ) : (
                      <Button
                        title="Không có CV"
                        variant="outlined"
                        style={{ color: "gray", backgroundColor: "white", borderColor: "gray" }}
                        disabled
                      >
                        CV
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
        {/* End table widget content */}
      </>
    )}
    </DialogContent>
  </BootstrapDialog>
</>
)}

export default RecommendModal;