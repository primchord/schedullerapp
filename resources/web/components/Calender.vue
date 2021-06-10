<template>
  <div>
    <h3 class="mt-5">Calender</h3>
    <v-sheet tile height="54" class="d-flex">
      <v-btn outlined small class="ma-4" @click="setToday">Today</v-btn>
      <v-btn icon class="ma-2" @click="$refs.calendar.prev()">
        <v-icon color="blue">mdi-chevron-left</v-icon>
      </v-btn>
      <v-btn class="ma-2" text>{{ title }}</v-btn>
      <v-btn icon class="ma-2" @click="$refs.calendar.next()">
        <v-icon color="blue">mdi-chevron-right</v-icon>
      </v-btn>
    </v-sheet>
    <v-sheet height="600">
      <v-calendar
        locale="ja-jp"
        :day-format="(timestamp) => new Date(timestamp.date).getDate()"
        :month-format="
          (timestamp) => new Date(timestamp.date).getMonth() + 1 + ' /'
        "
        ref="calendar"
        v-model="value"
        type="month"
        :events="lists"
        @click:date="editData"
      ></v-calendar>
    </v-sheet>
    <v-row justify="center">
      <v-dialog v-model="dialog" persistent max-width="700px">
        <v-card>
          <v-card-title>
            <span class="text-h5">{{ schDate }}の予定</span>
            <v-spacer></v-spacer>
            <v-btn
              color="blue darken-1"
              class="white--text"
              small
              @click="addDialog = true"
              >Add</v-btn
            >
          </v-card-title>
          <v-card-text>
            <v-container>
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
                      <th class="text-center">
                        <span class="text-subtitle-1">削 除</span>
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="item in dayEvents" :key="item.start">
                      <td>
                        <v-text-field
                          :value="item.start.slice(0, 16)"
                          @change="(e) => updateData(item.id, e, 'start')"
                        ></v-text-field>
                      </td>
                      <td>
                        <v-text-field
                          :value="item.place"
                          @change="(e) => updateData(item.id, e, 'place')"
                        ></v-text-field>
                      </td>

                      <td>
                        <v-text-field
                          :value="item.name"
                          @change="(e) => updateData(item.id, e, 'name')"
                        ></v-text-field>
                      </td>

                      <td class="text-center">
                        <v-btn
                          fab
                          dark
                          small
                          text
                          outlined
                          color="primary"
                          @click="deleteItem(item.id)"
                        >
                          <v-icon dark small> mdi-close </v-icon>
                        </v-btn>
                      </td>
                    </tr>
                  </tbody>
                </template>
              </v-simple-table>
            </v-container>
            <small>*indicates required field</small>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text @click="editDialogClose">
              Close
            </v-btn>
            <!-- <v-btn color="blue darken-1" text @click="dialog = false">
              Save
            </v-btn> -->
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>
    <v-row justify="center">
      <v-dialog v-model="addDialog" persistent max-width="600px">
        <v-card ref="form">
          <v-card-title>
            <span class="text-h5">予定追加</span>
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12" sm="6">
                  <v-text-field
                    label="日にち*"
                    required
                    :value="schDate"
                    type="date"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" sm="6">
                  <v-text-field
                    ref="start"
                    label="時間"
                    type="time"
                    v-model="addData.start"
                  ></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-text-field
                    ref="place"
                    label="場 所"
                    type="text"
                    v-model="addData.place"
                  ></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-text-field
                    ref="name"
                    label="内 容*"
                    type="text"
                    v-model="addData.name"
                    required
                  ></v-text-field>
                </v-col> </v-row
            ></v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text @click="addDialogClose">
              Cancel
            </v-btn>
            <v-btn color="blue darken-1" text @click="addSchedule">
              Save
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>
  </div>
</template>
<script>
import axios from 'axios';
import moment from 'moment';
export default {
  components: {},
  props: {
    lists: {
      type: Array,
      required: true,
    },
  },
  data: () => ({
    addDialog: false,
    dialog: false,
    value: moment().format('yyyy-MM-DD'),
    mode: 'stack',
    modes: ['stack', 'column'],
    weekday: [0, 1, 2, 3, 4, 5, 6],
    events: [],
    schDate: undefined,
    dayEvents: [],
    addData: [
      {
        start: '',
        place: '',
        name: '',
      },
    ],
  }),
  computed: {
    title() {
      return moment(this.value).format('yyyy年 M月');
    },
  },
  methods: {
    async editData({ date }) {
      this.schDate = date;
      const res = await this.lists.filter((elm) => {
        // listsからクリック日とマッチするものを返す
        return ~elm.start.indexOf(date);
      });
      this.dayEvents = res;
      if (!res.length) {
        this.addDialog = !this.addDialog;
      } else {
        this.dialog = !this.dialog;
      }
    },
    setToday() {
      this.value = moment().format('yyyy-MM-DD');
    },
    async deleteItem(id) {
      const res = window.confirm('本当に削除しますか？');
      const url = `api/delete/${id}`;
      if (res) {
        await axios.get(url);
        this.init();
      }
    },
    async addSchedule() {
      const data = {
        start: this.schDate + ' ' + this.addData.start,
        place: this.addData.place,
        name: this.addData.name,
      };
      const url = 'api/addschedule';
      await axios.post(url, data);
      this.init();
    },
    init() {
      this.$router.go({ path: this.$router.currentRoute.path, force: true });
    },
    addDialogClose() {
      this.$refs.start.reset();
      this.$refs.place.reset();
      this.$refs.name.reset();
      this.addDialog = !this.addDialog;
    },
    async updateData(id, editVal, editCol) {
      const url = 'api/update';
      const data = {
        id,
        val: editVal,
        column: editCol,
      };
      const res = await axios.post(url, data);
      console.log(res.data);
    },
    editDialogClose() {
      this.dialog = !this.dialog;
      this.init();
    },
  },
};
</script>
<style scoped></style>
