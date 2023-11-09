import React from 'react';
import { Route, Navigate } from 'react-router-dom';
import { AuthContext } from '../contexts/AuthContext';
import { useContext } from 'react';

const PrivateRoute = ({ element }) => {
    const {user} = useContext(AuthContext);
  const isLoggedIn = checkUserLoggedIn(user); 
  return isLoggedIn ? element : <Navigate to="/login" replace />;
};

const checkUserLoggedIn = (user) => {
  return user != null;
};

export default PrivateRoute;
