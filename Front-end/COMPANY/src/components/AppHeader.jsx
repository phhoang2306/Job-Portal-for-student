import { AppBar, Badge, Box, IconButton, Toolbar, Typography } from "@mui/material";
import MenuIcon from '@mui/icons-material/Menu'
import SettingsIcon from '@mui/icons-material/Settings';
import LogoutIcon from '@mui/icons-material/Logout';
import { useProSidebar } from "react-pro-sidebar";
import { AuthContext } from "../contexts/AuthContext";
import { useContext, useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';

function AppHeader() {
    const { collapseSidebar, toggleSidebar, collapsed, broken } = useProSidebar();
    const { dispatch,user, role, token  } = useContext(AuthContext);
    const [Profile, setProfile] = useState([]);
    const navigate = useNavigate();


    const handleLogout = () => {
        dispatch({ type: "LOGOUT" });
        navigate("/login")
      };

    return <AppBar position="sticky" sx={styles.appBar}>
        <Toolbar >
            <IconButton onClick={() => broken ? toggleSidebar() : collapseSidebar()} color="secondary">
                <MenuIcon />
            </IconButton>
            <Typography variant="h1" sx={{ color: 'white', fontSize: '20px' }}>FinDev </Typography>
            <Box
                sx={{ flexGrow: 1 }} />
            <p>
                <span style={{ color: 'white', fontSize: '20px' }}>Xin chào,</span>{" "}
                <span style={{ color: 'white', fontSize: '20px' }}>{user?.username}</span>
              </p>
            
            <IconButton title="Sign Out" color="secondary" onClick={handleLogout}>
                <LogoutIcon />
            </IconButton>
        </Toolbar>
    </AppBar>;
}

/** @type {import("@mui/material").SxProps} */
const styles = {
    appBar: {
        bgcolor: 'neutral.main'
    },
    appLogo: {
        borderRadius: 2,
        width: 80,
        marginLeft: 2,
        cursor: 'pointer'
    }
}

export default AppHeader;