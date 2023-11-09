import React from "react";
import ReactDOM from "react-dom";
import {AuthContextProvider} from "./contexts/AuthContext";
import "./index.css";
import App from "./App";
import { BrowserRouter} from "react-router-dom";

ReactDOM.render(
  <React.StrictMode>
  <AuthContextProvider>
    <BrowserRouter>
      <App />
    </BrowserRouter>
  </AuthContextProvider>
  </React.StrictMode>,
  document.getElementById("root")
);
