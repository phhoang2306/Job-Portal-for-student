import { createBrowserRouter, Route, Routes } from 'react-router-dom';
import Employers from '../containers/Employers/Employers';
import ManageJobs from '../containers/ManageJobs/ManageJob';
import PrivateRoute from './PrivateRoute';
import ChangePassword from '../containers/change-password/ChangePassword';
import PostJob from '../containers/PostJob/PostJob';
import Profile from '../containers/Profile/Profile';
import Application from '../containers/Applications/Applications';

const AppRoutes = () => {
    return <Routes>
        <Route path='/' element={<PrivateRoute element={<Profile />} />} />
        <Route path='/nhan-vien' element={<PrivateRoute element={<Employers />} />} />
        <Route path='/danh-sach-cong-viec' element={<PrivateRoute element={<ManageJobs />} />} />
        <Route path='/doi-mat-khau' element={<PrivateRoute element={<ChangePassword />} />} />
        <Route path='/dang-tuyen' element={<PrivateRoute element={<PostJob />} />} />
        <Route path='/danh-sach-ung-tuyen' element={<PrivateRoute element={<Application />} />} />
    </Routes>
};

export default AppRoutes;