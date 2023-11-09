import PropTypes from 'prop-types';
import Button from '@mui/material/Button';
import { styled } from '@mui/material/styles';
import Dialog from '@mui/material/Dialog';
import DialogTitle from '@mui/material/DialogTitle';
import DialogContent from '@mui/material/DialogContent';
import IconButton from '@mui/material/IconButton';
import CloseIcon from '@mui/icons-material/Close';
import Typography from '@mui/material/Typography';
import { useState } from 'react';
import styles from './Timetable.module.css';
import CalendarMonthIcon from '@mui/icons-material/CalendarMonth';

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

const Timetable = ({ timetable }) => {
  const [open, setOpen] = useState(false);
  const handleClickOpen = () => {
    setOpen(true);
  };
  const handleClose = () => {
    setOpen(false);
  };

  const isCellActivated = (rowIndex, columnIndex) => {
    if (!timetable) {
      return false; 
    }
    const coordinatesArray = timetable.split(';'); 
  return coordinatesArray.some((coordinate) => coordinate === `${columnIndex},${rowIndex}`);
  };

  const getCellClassName = (rowIndex, columnIndex) => {
    const cellClasses = [styles.cell];
    if (isCellActivated(rowIndex, columnIndex)) {
      cellClasses.push(styles.activatedCell);
    }
    return cellClasses.join(' ');
  };

  return (
    <>
      <Button variant="outlined" onClick={handleClickOpen} title="Xem thời gian biểu">
        <CalendarMonthIcon/>
      </Button>
      <BootstrapDialog
        sx={{
          '& .MuiDialog-paper': {
            width: '100%',
            height: '100%',
            margin: 0,
            maxWidth: 'none',
            maxHeight: 'none',
            borderRadius: 0,
            overflow: 'hidden',
            display: 'flex',
            flexDirection: 'column',
          },
        }}
        onClose={handleClose}
        aria-labelledby="customized-dialog-title"
        open={open}
      >
        <BootstrapDialogTitle id="customized-dialog-title" onClose={handleClose}>
          Thời gian BẬN của ứng viên
        </BootstrapDialogTitle>
        <DialogContent dividers>
          <div className={styles.container}>
            <table className={styles.table}>
              <thead>
                <tr>
                  <th className={styles.columnHeader}></th>
                  <th className={styles.columnHeader}>Thứ Hai</th>
                  <th className={styles.columnHeader}>Thứ Ba</th>
                  <th className={styles.columnHeader}>Thứ Tư</th>
                  <th className={styles.columnHeader}>Thứ Năm</th>
                  <th className={styles.columnHeader}>Thứ Sáu</th>
                  <th className={styles.columnHeader}>Thứ Bảy</th>
                  <th className={styles.columnHeader}>Chủ Nhật</th>
                </tr>
              </thead>
              <tbody>
                {Array.from(Array(24).keys()).map((hour) => (
                  <tr key={hour}>
                    <td className={styles.rowHeader}>{`${hour}h - ${hour + 1}h`}</td>
                    {Array.from(Array(7).keys()).map((day) => {
                      const columnIndex = day + 1;
                      const rowIndex = hour;
                      return (
                        <td
                          key={columnIndex}
                          className={getCellClassName(rowIndex, columnIndex)}
                        ></td>
                      );
                    })}
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </DialogContent>
      </BootstrapDialog>
    </>
  );
};

export default Timetable;
