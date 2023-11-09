import { Avatar, Box, Typography, useTheme } from "@mui/material";
import { Menu, MenuItem, Sidebar, useProSidebar } from "react-pro-sidebar";
import { Link } from 'react-router-dom';
import { useLocation } from 'react-router-dom';
import { AuthContext } from "../contexts/AuthContext";
import { useContext, useState } from 'react';
import ManageAccountsIcon from '@mui/icons-material/ManageAccounts';
import AccountCircleIcon from '@mui/icons-material/AccountCircle';
import FormatListBulletedIcon from '@mui/icons-material/FormatListBulleted';
import WorkIcon from '@mui/icons-material/Work';
import SettingsIcon from '@mui/icons-material/Settings';
import ReceiptLongIcon from '@mui/icons-material/ReceiptLong';
function SideNav() {
    const { collapsed } = useProSidebar();
    const theme = useTheme();
    const location = useLocation();

    return (
      <Sidebar
        style={{ height: "100%", top: 'auto' }}
        breakPoint="md"
        backgroundColor={theme.palette.neutral.light}
      >
        <Menu
          menuItemStyles={{
            button: ({ level, active }) => {
              return {
                backgroundColor: active ? theme.palette.neutral.medium : undefined,
              };
            },
          }}
        >
          <Link to="/">
            <MenuItem
              active={location.pathname === "/"}
              icon={<AccountCircleIcon />}
            >
              <Typography variant="body2" sx={{ fontSize: 18 }}>Hồ sơ công ty</Typography>
            </MenuItem>
          </Link>
  
          <Link to="/nhan-vien">
            <MenuItem
              active={location.pathname === "/nhan-vien"}
              icon={<ManageAccountsIcon />}
            >
              <Typography variant="body2" sx={{ fontSize: 18 }}>Nhân viên</Typography>
            </MenuItem>
          </Link>
  
          <Link to="/dang-tuyen">
            <MenuItem
              active={location.pathname === "/dang-tuyen"}
              icon={<WorkIcon />}
            >
              <Typography variant="body2" sx={{ fontSize: 18 }}>Đăng tuyển</Typography>
            </MenuItem>
          </Link>
  
          <Link to="/danh-sach-cong-viec">
            <MenuItem
              active={location.pathname === "/danh-sach-cong-viec"}
              icon={<FormatListBulletedIcon />}
            >
              <Typography variant="body2" sx={{ fontSize: 18 }}>Công việc</Typography>
            </MenuItem>
          </Link>

          <Link to="/danh-sach-ung-tuyen">
            <MenuItem
              active={location.pathname === "/danh-sach-ung-tuyen"}
              icon={<ReceiptLongIcon />}
            >
              <Typography variant="body2" sx={{ fontSize: 18 }}>Đơn ứng tuyển</Typography>
            </MenuItem>
          </Link>
  
          <Link to="/doi-mat-khau">
            <MenuItem
              active={location.pathname === "/doi-mat-khau"}
              icon={<SettingsIcon />}
            >
              <Typography variant="body2" sx={{ fontSize: 18 }}>Đổi mật khẩu</Typography>
            </MenuItem>
          </Link>
        </Menu>
      </Sidebar>
    );
}

export default SideNav;
