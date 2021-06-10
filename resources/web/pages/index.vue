<template>
  <v-container>
    <Weekly :lists="weekLists" />
    <Calender :lists="lists" />
  </v-container>
</template>
<script>
// import axios from "axios";
import moment from 'moment';
export default {
  Calender: () => import('~/components/Calender.vue'),
  Weekly: () => import('~/components/Weekly.vue'),
  async asyncData({ app, $getMonthlyData }) {
    const startWeekDay = moment().day(0).format('yyyy-MM-DD')
    const endWeekDay = moment().day(6).format('yyyy-MM-DD') + 1
    const lists = await app.$getMonthlyData();
    const weekLists = lists.filter(elm => {
      return elm.start >= startWeekDay && elm.start <= endWeekDay
    })
    return { lists, weekLists };
  },
  data: () => ({
  }),
  created(){
  },
  methods: {
    // async getUsers() {
    //   const users = await axios.get("api/getusers");
    // },
  },
};
</script>

<style></style>
