import React from 'react'
import ReactDOM from 'react-dom/client'
import { ProSidebarProvider } from 'react-pro-sidebar'
import App from './App'
import './styles/index.scss'
import {AuthContextProvider} from "./contexts/AuthContext";




ReactDOM.createRoot(document.getElementById('root')).render(
  <React.StrictMode>
    <AuthContextProvider>
      <ProSidebarProvider>
        <App />
      </ProSidebarProvider>
    </AuthContextProvider>
  </React.StrictMode>,
)
