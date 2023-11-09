import React, {  useState } from "react";
import { Routes, Route, Navigate } from "react-router-dom";
import { Navbar} from "./components";
import {Companys, Login, Users, Mods, ReportsJob, ReportsCom} from "./pages";
import "./App.css";
import { AuthContext } from "./contexts/AuthContext";
import { useContext } from "react";

const App = () => {
  const [isLoggedIn, setIsLoggedIn] = useState(false);
  const { user } = useContext(AuthContext);


  const checkLogin = () => {
  if (!user) {
    return <Navigate to="/login" />;
  }
  if(user.username === "Admin")
  {
  return (
    <div className="app-container">
        <div className="navbar-container">
          <Navbar />
        </div>
    <div className="routes-container">
        <Routes>
          <Route path="/" element={<Mods />} />
          <Route path="/mods" index element={<Mods />} />
          <Route path="/nguoi-dung" index element={<Users />} />
          <Route path="/cong-ty" index element={<Companys />} />
          <Route path="/bao-cao-cong-ty" index element={<ReportsCom />} />
          <Route path="/bao-cao-cong-viec" index element={<ReportsJob />} />
        </Routes>
      </div>
    </div>
  );
}
  else 
  {return (
    <div className="app-container">
        <div className="navbar-container">
          <Navbar />
        </div>
    <div className="routes-container">
      <Routes>
        <Route path="/" element={<Users />} />
        <Route path="/nguoi-dung" index element={<Users />} />
        <Route path="/cong-ty" index element={<Companys />} />
        <Route path="/bao-cao-cong-ty" index element={<ReportsCom />} />
        <Route path="/bao-cao-cong-viec" index element={<ReportsJob />} />
      </Routes>
    </div>
  </div>

  )
  }
};

  return (
      <Routes>
        <Route path="/login" element={<Login setIsLoggedIn={setIsLoggedIn} />} />
        <Route path="*" element={checkLogin()} isLoggedIn={isLoggedIn} />
      </Routes>
  );
}
export default App;
