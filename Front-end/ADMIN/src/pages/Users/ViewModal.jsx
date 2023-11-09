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
import {localUrl} from '../../utils/path.js'
import axios from "axios";
import { Paper, Avatar } from '@mui/material';
import VisibilityIcon from '@mui/icons-material/Visibility';



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

const Views = ({id, token}) => {
  const [open, setOpen] = useState(false);
  const [userProfile, setUserProfile] = useState([]);
  const handleClickOpen = () => {
    setOpen(true);
  };
  const handleClose = () => {
    setOpen(false);
  };

  const getuserProfile = async () => {
    try {
      const res = await axios.get(`${localUrl}/user-profiles/${id}`,
      {
        headers: 
        {
          'Content-Type': 'application/json',
          'Authorization': token
        }
      }
      )
      setUserProfile(res.data.data.user_profile);
    } catch (err) {
    }
  }


  useEffect(() => {
    getuserProfile();
  }, [id]);


return(
    <>
        <Button 
          variant="outlined" 
          onClick={handleClickOpen}
          title="Xem hồ sơ"
        >
          <VisibilityIcon/>
        </Button>
        <BootstrapDialog
            onClose={handleClose}
            aria-labelledby="customized-dialog-title"
            open={open}
        >
            <BootstrapDialogTitle id="customized-dialog-title" onClose={handleClose}>
            Thông tin người dùng
            </BootstrapDialogTitle>
            <DialogContent dividers>
            {userProfile && (
            <Paper elevation={3} sx={{ padding: 2 , display: 'flex', flexDirection: 'column', alignItems: 'center' }}>
            <Avatar
              alt={userProfile.full_name}
              src={userProfile.avatar}
              sx={{ width: 100, height: 100, marginBottom: 2 }}
            />
            <Typography variant="h5" gutterBottom>
              {userProfile.full_name}
            </Typography>
            <Typography gutterBottom>
                <strong>Giới thiệu: </strong> {userProfile.about_me}
            </Typography>
            <Typography gutterBottom>
                <strong>Vị trí sở trường: </strong> {userProfile.good_at_position}
            </Typography>
            <Typography gutterBottom>
            <strong>Kinh nghiệm làm việc: </strong>
              {userProfile.year_of_experience === "0" && 'Không có'}
              {userProfile.year_of_experience !== "0" && `${userProfile.year_of_experience} năm`}
            </Typography>
            <Typography gutterBottom>
                <strong>Ngày sinh: </strong> {userProfile.date_of_birth}
            </Typography>
            <Typography gutterBottom>
                <strong>Giới tính: </strong> {userProfile.gender}
            </Typography>
            <Typography gutterBottom>
              <strong>Địa chỉ: </strong> {userProfile.address}
            </Typography>
            <Typography gutterBottom>
             <strong>Email: </strong> {userProfile.email}
            </Typography>
            <Typography gutterBottom>
             <strong>Điện thoại liên lạc: </strong> {userProfile.phone}
            </Typography>
          </Paper>
            )}
            </DialogContent>
        </BootstrapDialog>
    </>
)}

export default Views;