<template>
  <div>
    <h3>Weekly</h3>
    <v-simple-table>
      <template #default>
        <thead>
          <tr>
            <th class="text-left">
              <span class="text-subtitle-1">日 時</span>
            </th>
            <th class="text-left">
              <span class="text-subtitle-1">場 所</span>
            </th>
            <th class="text-left">
              <span class="text-subtitle-1">内 容</span>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="item in lists"
            :key="item.start"
            :class="[~item.start.indexOf(value) ? 'textbold' : '']"
          >
            <td>
              {{ item.start.slice(5, 16) }}
            </td>
            <td>{{ item.place }}</td>
            <td>{{ item.name }}</td>
          </tr>
        </tbody>
      </template>
    </v-simple-table>
  </div>
</template>
<script>
import moment from 'moment';
export default {
  props: {
    lists: {
      type: Array,
      require: true,
    },
  },
  data: () => ({
    value: moment().format('yyyy-MM-DD'),
  }),
};
</script>
<style scoped>
.textbold {
  font-weight: bold;
  color: rgb(97, 2, 2);
}
</style>
