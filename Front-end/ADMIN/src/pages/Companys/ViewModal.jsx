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
import { Paper, Avatar, Link } from '@mui/material';
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

const Views = ({id}) => {
  const [open, setOpen] = useState(false);
  const [companyProfile, setCompanyProfile] = useState([]);

  const handleClickOpen = () => {
    setOpen(true);
  };
  const handleClose = () => {
    setOpen(false);
  };

  const getCompanyProfile = async () => {
    try {
      const res = await axios.get(`${localUrl}/company-profiles/${id}`)
      setCompanyProfile(res.data.data.company_profile);
    } catch (err) {
    }
  }


  useEffect(() => {
    getCompanyProfile();
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
            Thông tin công ty
            </BootstrapDialogTitle>
            <DialogContent dividers>
            {companyProfile && (
            <Paper elevation={3} sx={{ padding: 2 , display: 'flex', flexDirection: 'column', alignItems: 'center' }}>
            <Avatar
              alt={companyProfile.name}
              src={companyProfile.logo}
              sx={{ width: 100, height: 100, marginBottom: 2 }}
            />
            <Typography variant="h5" gutterBottom>
              {companyProfile.name}
            </Typography>
            <Typography gutterBottom>
                <strong>Mô tả:</strong> {companyProfile.description}
            </Typography>
            <Typography gutterBottom>
                <strong>Địa chỉ:</strong> {companyProfile.address}
            </Typography>
            <Typography gutterBottom>
             <strong>Kích thước công ty:</strong> {companyProfile.size}
            </Typography>
            <Typography gutterBottom>
                <strong>Điện thoại:</strong> {companyProfile.phone}
            </Typography>
            <Typography gutterBottom>
                <strong>Email:</strong> {companyProfile.email}
            </Typography>
            <Typography gutterBottom>
                <strong>Trang web:</strong> <Link href={companyProfile.site} target="_blank">{companyProfile.site}</Link>
            </Typography>
          </Paper>
            )}
            </DialogContent>
        </BootstrapDialog>
    </>
)}

export default Views;