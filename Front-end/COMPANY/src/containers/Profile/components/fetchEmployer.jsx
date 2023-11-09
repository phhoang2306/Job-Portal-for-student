// fetchProfile.js
import { localUrl } from '../../../utils/path';

const fetchEmployer = async (id, token) => {
    const url = `${localUrl}/employer-profiles/${id}`;
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
      return data.data.employer_profile.company_id;
    } catch (error) {
      console.error(error);
      // throw error;
    }
  };

  export default fetchEmployer;
