import React, { useState } from 'react'
import './App.css'
import '@fontsource/roboto/300.css';
import '@fontsource/roboto/400.css';
import '@fontsource/roboto/500.css';
import '@fontsource/roboto/700.css';
import CssBaseline from '@mui/material/CssBaseline';
import { Sidebar, Menu, MenuItem, SubMenu, ProSidebarProvider } from 'react-pro-sidebar';
import { Box, Button, Container, ThemeProvider, Typography } from '@mui/material';
import SideNav from './components/SideNav';
import AppHeader from './components/AppHeader';
import theme from './config/theme';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import AppRoutes from './router/AppRoutes';
import Login from './pages/login/Login'
import Register from './pages/register/Register'

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/login" element={<Login />} />
        <Route path="/register" element={<Register />} />
        <Route
          path="/*"
          element={
        <>
          <React.Fragment>
            <ThemeProvider theme={theme}>
              <ProSidebarProvider>
                <CssBaseline />
                <AppHeader />
                <Box sx={styles.container}>
                  <SideNav />
                  <Box component={'main'} sx={styles.mainSection}>
                    <AppRoutes />
                  </Box>
                </Box>
              </ProSidebarProvider>
            </ThemeProvider>
          </React.Fragment>
        </>
          }
        />
      </Routes>
    </BrowserRouter>
  )
}

/**
 * @type {import('@mui/material').SxProps}
 */
const styles = {
  container: {
    display: 'flex',
    bgcolor: 'neutral.light',
    height: 'calc(100% - 64px)'
  },
  mainSection: {
    p: 4,
    width: '100%',
    height: '100%',
    overflow: 'auto',
  }
}
export default App
