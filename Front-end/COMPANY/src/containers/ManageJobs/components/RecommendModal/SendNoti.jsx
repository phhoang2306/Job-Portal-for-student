import * as React from 'react';
import Button from '@mui/material/Button';
import TextField from '@mui/material/TextField';
import Dialog from '@mui/material/Dialog';
import DialogActions from '@mui/material/DialogActions';
import DialogContent from '@mui/material/DialogContent';
import DialogContentText from '@mui/material/DialogContentText';
import DialogTitle from '@mui/material/DialogTitle';
import SendIcon from '@mui/icons-material/Send';
import axios from "axios";
import {localUrl} from '../../../../utils/path'

export default function SendNoti({job_id, token, user_id}) {
  const [open, setOpen] = React.useState(false);
  const [refLink, setRefLink] = React.useState("");

  const handleClickOpen = () => {
    setOpen(true);
  };

  const handleClose = () => {
    setOpen(false);
  };

  const handleSubmit = async (e) => {
    e.preventDefault(); 
    try {
      const res = await axios.post(
        `${localUrl}/user-profiles/noti/job-invite`,
        {
          "job_id" : job_id,
          "user_id" : user_id,
          "refer_link" : refLink,
        },
        {
          headers: {
            'Content-Type': 'application/json',
            'Authorization': token
          },
        }
      );
      console.log(res.data)
      handleClose();
      alert("Đã gửi lời mời ứng tuyển thành công !")
    } catch (err) {
      alert("Gửi lời mời ứng tuyển thất bại!")
      console.log(err)
    }
  };

  return (
    <>
        <Button
        title="Gửi lời mời ứng tuyển"
        variant="outlined"
        style={{ color: "blue", backgroundColor: "white", borderColor: "blue" }}
        onClick={handleClickOpen}
        >
        <SendIcon/>
        </Button>
      <Dialog open={open} onClose={handleClose}>
        <DialogTitle>Gửi lời mời ứng tuyển</DialogTitle>
        <DialogContent>
          <DialogContentText>
           Bạn có thể nhập đường dẫn bất kì để gửi đến ứng viên (Ví dụ: đường dẫn meeting,... ) (không bắt buộc)
          </DialogContentText>
          <TextField
            autoFocus
            margin="dense"
            id="name"
            label="Đường dẫn muốn gửi"
            type="text"
            fullWidth
            variant="standard"
            value={refLink}
            onChange={(e) => setRefLink(e.target.value)}
          />
        </DialogContent>
        <DialogActions>
          <Button onClick={handleClose}>Hủy</Button>
          <Button onClick={handleSubmit}>Gửi</Button>
        </DialogActions>
      </Dialog>
    </>
  );
}
