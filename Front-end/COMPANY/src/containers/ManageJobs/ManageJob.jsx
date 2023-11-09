import ManageJobTable from "./components/ManageJobTable";
import {Typography } from "@mui/material";
const ManageJobs = () => {
  return (
    <>
          <Typography sx={{mb: 2}} variant="h5">Công việc đăng tuyển</Typography>
          {/* breadCrumb */}

          <div className="row">
            <div className="col-lg-12">
              {/* <!-- Ls widget --> */}
              <div className="ls-widget">
                <ManageJobTable/>
              </div>
            </div>
          </div>
      </>
  );
};

export default ManageJobs;
