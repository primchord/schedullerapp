import axios from 'axios';


async function getMonthlyData(){
  const url = 'http://localhost:8000/api/getall'
  const res = await axios.get(url)
  return res.data
};

export default ({ app }, inject) => {
  inject('getMonthlyData', getMonthlyData);
};