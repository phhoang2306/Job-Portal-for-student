// fetchProfile.js
import { localUrl } from '../../../utils/path';

const fetchCompany = async (id, token) => {
    const url = `${localUrl}/company-profiles/${id}`;
    const headers = {
      Accept: 'application/json',
      Authorization: `token`,
    };
  
    try {
      const response = await fetch(url, { method: 'GET', headers });
      if (!response.ok) {
        // throw new Error('Failed to fetch profile data');
        console.error('Phiên làm việc đã hết hạn, vui lòng đăng nhập lại');
      }
  
      const data = await response.json();
      return data;
    } catch (error) {
      console.error(error);
      // throw error;
    }
  };

  export default fetchCompany;