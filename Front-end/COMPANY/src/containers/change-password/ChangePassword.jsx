import Form from "./components/Form";
import { AuthContext } from "../../contexts/AuthContext";
import { useContext, useState } from 'react';
import {Typography } from "@mui/material";
const ChangePassword = () => {
  const {user, token } = useContext(AuthContext);
  return (
    <>
          <Typography sx={{mb: 2}} variant="h5">Đổi mật khẩu</Typography>
          {/* breadCrumb */}

          <div className="row">
            <div className="col-lg-12">
              {/* <!-- Ls widget --> */}
              <div className="ls-widget">
                <Form user={user} token={token}/>
              </div>
            </div>
          </div>
      </>
  );
};

export default ChangePassword;
