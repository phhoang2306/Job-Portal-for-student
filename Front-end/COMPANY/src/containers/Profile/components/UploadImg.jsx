import { localUrl } from "../../../utils/path";
import axios from "axios"

export async function UploadImg (file, user, token) {
  //conso.log("File",file)
  //conso.log("user", user)
  //conso.log("token",token)
  const formData = new FormData();
  formData.append("avatar", file); 
  try {
    const response = await fetch(
      `${localUrl}/company-profiles/logo/${user.id}`,
      {
        method: "POST",
        headers: {
          Accept: "application/json",
          Authorization: `Bearer ${token}`,
        },
        body: formData,
      }
    );
    if(!response.ok){
      alert("Lỗi upload ảnh");
    }
    else{ 
      //conso.log(response)
      //window.location.reload();
    }
  } catch (err) {
    //conso.log(err);
  }
};

export default UploadImg;
