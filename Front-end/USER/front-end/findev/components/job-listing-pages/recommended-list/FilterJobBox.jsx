import Link from "next/link";
// import { useRouter } from "next/router";
import { use, useEffect, useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import {
  addCategory,
  addDatePosted,
  addExperienceSelect,
  addJobTypeSelect,
  addKeyword,
  addLocation,
  addPerPage,
  addSalary,
  addSort,
} from "../../../features/filter/filterSlice";
import Pagination from "./PaginationRCM";
// import JobSelect from "../components/JobSelect";
import { recommendUrl } from "../../../utils/path";
// import { AlignHorizontalCenter } from "@mui/icons-material";

const FilterJobBox = () => {
  const { user } = useSelector((state) => state.user);
  // console.log(user.userAccount.id);
  const [jobs, setJobs] = useState(undefined);
  const [currentPage, setCurrentPage] = useState(1);
  const [jobsPerPage, setJobsPerPage] = useState(10);
  const { jobList, jobSort } = useSelector((state) => state.filter);
  const [paginationLinks, setPaginationLinks] = useState([]);
  useEffect(() => {
    if (user) {
      const recommendJobsDataKey = "recommendJobsData";
      const recommendJobsData = localStorage.getItem(recommendJobsDataKey);
      if (!recommendJobsData) {
        const queryUrl = `${recommendUrl}${user.userAccount.id}&limit=30`;
        // console.log(queryUrl);

        const getJobs = async () => {
          try {
            const res = await fetch(queryUrl);
            const data = await res.json();
            console.log(data);
            if(data.error === false) {
              setJobs(data);
              // check if data.data.jobs.data has lenght > 10 then append pagination links
                if (data.data.jobs.data.length > 10) {
                    // console.log(data.data.jobs.data.length);
                    let totalPage = Math.ceil(data.data.jobs.data.length / 10);
                    // console.log(totalPage);
                    let links = [];
                    for (let i = 1; i <= totalPage; i++) {
                        if(i === 1){
                            links.push({ label: i, active: true });
                        }
                        else links.push({ label: i, active: false });
                    }
                    setPaginationLinks(links);
                }
            }
            else if (data.status_code === 524){
                alert("Quá thời gian tìm kiếm công việc, vui lòng thử lại sau");
            }
          } catch (error) {
            console.error("Error fetching jobs:", error);
          }
        };
        getJobs();
      }
    }
  }, [user, currentPage]);

  const handlePageChange = (page) => {
    // check page is a number
    if (!isNaN(page)) {
      setCurrentPage(page);
      setPaginationLinks((prevLinks) => {
        const newLinks = prevLinks.map((link) => {
          if (link.label === page) {
            return { ...link, active: true };
          }
          return { ...link, active: false };
        });
        return newLinks;
      });
    } else if (page === "&laquo; Previous" && currentPage > 1) {
      setCurrentPage(currentPage - 1);
      setPaginationLinks((prevLinks) => {
        const newLinks = prevLinks.map((link) => {
          if (link.label === currentPage - 1) {
            return { ...link, active: true };
          }
          return { ...link, active: false };
        });
        return newLinks;
      });
    } else if (page === "Next &raquo;" && currentPage < 3) {
      setCurrentPage(currentPage + 1);
      setPaginationLinks((prevLinks) => {
        const newLinks = prevLinks.map((link) => {
          if (link.label === currentPage + 1) {
            return { ...link, active: true };
          }
          return { ...link, active: false };
        });
        return newLinks;
      });
    }
  };
  const {
    // keyword,
    // location,
    destination,
    category,
    datePosted,
    jobTypeSelect,
    experienceSelect,
    salary,
  } = jobList || {};
  // console.log(keyword, location);

  const { sort, perPage } = jobSort;
  const dispatch = useDispatch();

  // Filters
  const keywordFilter = (item) =>
    keyword !== ""
      ? item.jobTitle.toLowerCase().includes(keyword.toLowerCase())
      : item;

  const locationFilter = (item) =>
    location !== ""
      ? item?.location?.toLowerCase().includes(location.toLowerCase())
      : item;

  const destinationFilter = (item) =>
    item?.destination?.min >= destination?.min &&
    item?.destination?.max <= destination?.max;

  const categoryFilter = (item) =>
    category !== ""
      ? item?.category?.toLowerCase() === category.toLowerCase()
      : item;

  const jobTypeFilter = (item) =>
    item.jobType !== undefined &&
    jobTypeSelect !== "" &&
    item?.jobType[0]?.type.toLowerCase().split(" ").join("-") === jobTypeSelect
      ? item
      : undefined;

  const datePostedFilter = (item) =>
    datePosted !== "all" &&
    datePosted !== "" &&
    item?.created_at.toLowerCase().split(" ").join("-").includes(datePosted)
      ? item
      : undefined;

  const experienceFilter = (item) =>
    experienceSelect !== "" &&
    item?.experience.split(" ").join("-").toLowerCase() === experienceSelect
      ? item
      : undefined;

  const salaryFilter = (item) =>
    item?.totalSalary?.min >= salary?.min &&
    item?.totalSalary?.max <= salary?.max;

  const sortFilter = (a, b) =>
    sort === "des" ? a.id > b.id && -1 : a.id < b.id && -1;

  // Jobs content
  let content = undefined;
  if (jobs !== undefined) {
    // console.log(jobs);
    const filteredJobs = jobs?.data?.jobs?.data;
    if (filteredJobs?.length > 0) {
      // slice content to show 10 jobs per page base on current page
      content = filteredJobs
        .slice((currentPage - 1) * 10, currentPage * 10)
        .map((item) => (
          // console.log(item),
          <div className="job-block col-lg-6 col-md-12 col-sm-12" key={item.id}>
            <div className="inner-box">
              <div className="content">
                <span className="company-logo">
                  <Link
                    href={`/recommended-jobs/${item.job_id}`}
                    target="_blank"
                    rel="noopener noreferrer"
                  >
                    <img
                      src={item?.company_logo || "/images/logo.png"}
                      title={item?.company_name || "Company Logo"}
                      alt={item?.company_name || "Company Logo"}
                    />
                  </Link>
                  {/* <img src={item?.employer_profile.company_profile.logo} alt={item?.company} /> */}
                </span>
                <h4>
                  <Link
                    href={`/recommended-jobs/${item.job_id}`}
                    alt={item.title}
                    title={item.title}
                    target="_blank"
                    rel="noopener noreferrer"
                  >
                    {/* check if job title is longer than 50 character then truncate */}
                    {item.title.length > 50
                      ? item.title.slice(0, 50) + "..."
                      : item.title}
                  </Link>
                </h4>
                <ul className="job-info">
                  <li>
                    <span className="icon flaticon-briefcase"></span>
                    <Link
                      href={`/employer/${item?.company_id || 1}`}
                      alt={item?.company_name}
                      title={item?.company_name}
                    >
                      {item?.company_name.length > 12
                        ? item?.company_name.slice(0, 12) + "..."
                        : item?.company_name}
                    </Link>
                  </li>
                  <li>
                    <span className="icon flaticon-map-locator"></span>
                    {/* get first location from job_location array using slice
                                        and get text before ':' using split
                                    */}
                    {/* {item.location[0].split(":")[0]} */}
                    {item.location.split(":")[0] || "Không xác định"}
                  </li>
                  <li>
                    <span className="icon flaticon-clock-3"></span>{" "}
                    {item.deadline}
                  </li>
                  <li>
                    <span className="icon flaticon-money"></span>
                    {/* switch case for min_salary and max_salary cases:
                                    min_salary = -1 and max_salary = -1 => Thỏa thuận
                                    min_salary = 0 and max_salary = 0 => Không lương
                                    min_salary = 0 and max_salary > 0 => Lên đến max_salary
                                    min_salary > 0 and max_salary > 0 => Từ min_salary - max_salary
                                    */}
                    {item.min_salary === -1 && item.max_salary === -1
                      ? "Thỏa thuận"
                      : item.min_salary === 0 && item.max_salary === 0
                      ? "Không lương"
                      : item.min_salary === 0 && item.max_salary > 0
                      ? `Lên đến ${item.max_salary}tr`
                      : `${item.min_salary} - ${item.max_salary}tr`}
                  </li>
                </ul>
                <ul className="job-other-info">
                  <li className="time">{item.type}</li>
                  <li className="required">Số lượng: {item.recruit_num}</li>
                  <li className="yoe">
                    {/* if item.min_yoe == item.max_yoe -> min_yoe
                                    if item.min_yoe == item.max_yoe -> min_yoe == 0 -> Không yêu cầu kinh nghiệm
                                    if item.min_yoe == item.max_yoe -> min_yoe != 0 -> Kinh nghiệm từ min_yoe năm
                                    if item.min_yoe != item.max_yoe -> Kinh nghiệm từ min_yoe đến max_yoe năm
                                */}
                    {item.min_yoe === item.max_yoe
                      ? item.min_yoe === 0
                        ? "Không yêu cầu kinh nghiệm"
                        : `Kinh nghiệm từ ${item.min_yoe} năm`
                      : `Kinh nghiệm từ ${item.min_yoe} - ${item.max_yoe}
                                    năm`}
                  </li>
                </ul>
                {/* <button className="bookmark-btn">
                                <span className="flaticon-bookmark"></span>
                            </button> */}
              </div>
            </div>
          </div>
        ));
    } else {
      content = (
        <h1 style={{ textAlign: "center" }}>
          Không tìm thấy công việc, bạn hãy cập nhật đầy đủ các mục có dấu sao
          <span style={{ color: "red" }}> (*) </span>
          trong thông tin cá nhân của mình
        </h1>
      );
    }
  } else {
    content = user ? (
      <>
        <h1 style={{ textAlign: "center", color: "black" }}>
          <span
            className="fa fa-spinner fa-spin fa-fw"
            style={{ marginRight: "5px" }}
          ></span>
          Đang tải...
        </h1>
        <div className="text" style={{ textAlign: "center", color: "black" }}>
          Quá trình này có thể mất vài phút.
        </div>
      </>
    ) : (
      <>
        {/* <div className="ls-switcher">
                <JobSelect />
            </div> */}

        <div className="row">{content}</div>
        <div className="row">
          <div className="col-md-12">
            <h2 className="text-center">
              Bạn cần đăng nhập để sử dụng tính năng này
            </h2>
          </div>
        </div>
      </>
    );
  }

  // Event Handlers
  const sortHandler = (e) => {
    dispatch(addSort(e.target.value));
  };

  const perPageHandler = (e) => {
    const pageData = JSON.parse(e.target.value);
    dispatch(addPerPage(pageData));
    // console.log(pageData);
    setJobsPerPage(pageData.end - pageData.start);
    setCurrentPage(1);
  };

  const clearAll = () => {
    // dispatch(addKeyword(""));
    // dispatch(addLocation(""));
    // dispatch(addCategory(""));
    // dispatch(addJobTypeSelect(""));
    // dispatch(addDatePosted(""));
    // dispatch(addExperienceSelect(""));
    // dispatch(addSalary({ min: 0, max: 20000 }));
    // dispatch(addSort(""));
    dispatch(addPerPage({ count_per_page: 10 }));
    setJobsPerPage(10);
    setCurrentPage(1);
    dispatch(addPerPage({ start: 0, end: 10 }));
  };
  return (
    <>
      <div className="ls-switcher">
        {/* <JobSelect /> */}

        {/* <div className="sort-by">
                    {
                        // keyword !== "" ||
                        // location !== "" ||
                        // category !== "" ||
                        // jobTypeSelect !== "" ||
                        // datePosted !== "" ||
                        // experienceSelect !== "" ||
                        // salary?.min !== 0 ||
                        // salary?.max !== 20000 ||
                        // sort !== "" ||
                    perPage.end !== 10 ? (
                        <button
                            onClick={clearAll}
                            className="btn btn-danger text-nowrap me-2"
                            style={{ minHeight: "45px", marginBottom: "15px" }}
                        >
                            Về mặc định
                        </button>
                    ) : undefined}

                    <select
                        value={sort}
                        className="chosen-single form-select"
                        onChange={sortHandler}
                    >
                        <option value="">Sắp xếp theo</option>
                        <option value="asc">Mới nhất</option>
                        <option value="des">Cũ nhất</option>
                    </select>

                    <select
                        onChange={perPageHandler}
                        className="chosen-single form-select ms-3 "
                        value={JSON.stringify(perPage)}
                    >
                        <option value={JSON.stringify({ start: 0, end: 10 })}>10 mỗi trang</option>
                        <option value={JSON.stringify({ start: 0, end: 20 })}>20 mỗi trang</option>
                        <option value={JSON.stringify({ start: 0, end: 30 })}>30 mỗi trang</option>
                        <option value={JSON.stringify({ start: 0, end: 50 })}>50 mỗi trang</option>
                    </select>
                </div> */}
      </div>

      <div className="row">{content}</div>

      <Pagination
        jobs={jobs}
        handlePageChange={handlePageChange}
        paginationLinks={paginationLinks}
      />
    </>
  );
};

export default FilterJobBox;
