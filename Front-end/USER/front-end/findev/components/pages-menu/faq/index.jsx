import Breadcrumb from "../../common/Breadcrumb";
import LoginPopup from "../../common/form/login/LoginPopup";
import FooterDefault from "../../footer/common-footer";
import DefaulHeader from "../../header/DefaulHeader2";
import MobileMenu from "../../header/MobileMenu";
import FaqChild from "./FaqChild";

const Index = () => {
  return (
    <>
      {/* <!-- Header Span --> */}
      <span className="header-span"></span>

      <LoginPopup />
      {/* End Login Popup Modal */}

      <DefaulHeader />
      {/* <!--End Main Header --> */}

      <MobileMenu />
      {/* End MobileMenu */}

      {/* <Breadcrumb title="Faq's" meta="Faq's" /> */}
      {/* <!--End Page Title--> */}

      <section className="faqs-section">
        <div className="auto-container">
          <div className="sec-title text-center">
            <h2>FAQ</h2>
            {/* <div className="text">Home / Faq</div> */}
          </div>

          <h3>Các câu hỏi thường gặp</h3>
          {/* <!--Accordian Box--> */}
          <ul className="accordion-box">
            <FaqChild />
          </ul>

          {/* <h3>Suggestions</h3>
          <ul className="accordion-box mb-0">
            <FaqChild />
          </ul> */}
        </div>
      </section>
      {/* <!-- End Faqs Section --> */}

      <FooterDefault footerStyle="alternate5" />
      {/* <!-- End Main Footer --> */}
    </>
  );
};

export default Index;
