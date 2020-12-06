<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
    <link rel="stylesheet" href="{{ asset('css/schedule.css') }}">

</head>
<body>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js" type="text/javascript"></script>

<div id="app">
    <div id="app">
        <v-app id="inspire">
            <div>
                <v-sheet
                    tile
                    height="54"
                    color="grey lighten-3"
                    class="d-flex"
                >
                    <v-btn
                        icon
                        class="ma-2"
                        @click="$refs.calendar.prev()"
                    >
                        <v-icon>mdi-chevron-left</v-icon>
                    </v-btn>
                    <v-select
                        v-model="type"
                        :items="types"
                        dense
                        outlined
                        hide-details
                        class="ma-2"
                        label="type"
                    ></v-select>
                    <v-select
                        v-model="mode"
                        :items="modes"
                        dense
                        outlined
                        hide-details
                        label="event-overlap-mode"
                        class="ma-2"
                    ></v-select>
                    <v-select
                        v-model="weekday"
                        :items="weekdays"
                        dense
                        outlined
                        hide-details
                        label="weekdays"
                        class="ma-2"
                    ></v-select>
                    <v-spacer></v-spacer>
                    <v-btn
                        icon
                        class="ma-2"
                        @click="$refs.calendar.next()"
                    >
                        <v-icon>mdi-chevron-right</v-icon>
                    </v-btn>
                </v-sheet>
                <v-sheet height="600">
                    <v-calendar
                        ref="calendar"
                        v-model="value"
                        :weekdays="weekday"
                        :type="type"
                        category-show-all
                        :categories="category"
                        :events="events"
                        :event-overlap-mode="mode"
                        :event-overlap-threshold="30"
                        :event-color="getEventColor"
                        @change="getEvents"
                        @click:event="showEvent"
                    >
                    <template #day-body="{ date,week }">
                      <div
                        class="v-current-time"
                        :class="{ first: date === week[0].date }"
                        :style="{ top: nowY }"
                      ></div>
                    </template>

                  </v-calendar>
                    <v-menu
                        v-model="selectedOpen"
                        :close-on-content-click="false"
                        offset-x
                        :activator="selectedElement"
                    >
                        <v-card
                            color="grey lighten-4"
                            min-width="350px"
                            flat
                        >
                            <v-toolbar
                                :color="selectedEvent.color"
                                dark
                            >
                                <v-btn @click="pageChange(selectedEvent.id)" icon>
                                    <v-icon>mdi-pencil</v-icon>
                                </v-btn>

                                <v-toolbar-title v-html="selectedEvent.name"></v-toolbar-title>
                                <v-spacer></v-spacer>

                            </v-toolbar>
                            <v-card-text>
                                <span >@{{selectedEvent.start | moment}}</span><br>
                                <span >@{{selectedEvent.end | moment}}</span><br>
                                <span >@{{selectedEvent.cource}}</span>
                            </v-card-text>
                            <v-card-actions>
                              <v-btn
                                  text
                                  color="primary"
                                  @click=""
                              >
                                  予約変更
                              </v-btn>
                              <v-btn
                                  text
                                  color="error"
                                  @click="deleteConfirm(selectedEvent.id)"
                              >
                                  予約取消し
                              </v-btn>
                                <v-btn
                                    text
                                    color="secondary"
                                    @click="selectedOpen = false"
                                >
                                    閉じる
                                </v-btn>
                            </v-card-actions>
                        </v-card>

                    </v-menu>
                </v-sheet>

                <!-- 削除確認ダイアログを追加 -->
                  <v-dialog v-model="deleteDialog" persistent max-width="290">
                    <v-card>
                      <v-card-title class="headline">予約取消し確認</v-card-title>
                      <v-card-text>ID:@{{ deleteID }}の予約を取消してもよろしいですか？</v-card-text>
                      <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="green darken-1" text @click="deleteDialog = false">キャンセル</v-btn>
                        <v-btn color="green darken-1" text @click="deleteItem(deleteID)">実行</v-btn>
                      </v-card-actions>
                    </v-card>
                  </v-dialog>

            </div>
            @{{test}}


        </v-app>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    new Vue({
        el: '#app',
        vuetify: new Vuetify(),
        data: () => ({

            focus: "",
            test:"",
            type: 'category',
            types: [
              { text: '月' , value: 'month'},
              { text: '週' , value: 'week'},
              { text: '日' , value: 'category'},
            ],
            mode: 'stack',
            modes: ['stack', 'column'],
            weekday: [0, 1, 2, 3, 4, 5, 6],
            weekdays: [
                { text: '日 - 土', value: [0, 1, 2, 3, 4, 5, 6] },
                { text: '月 - 日', value: [1, 2, 3, 4, 5, 6, 0] },


            ],
            value: '',
            ready: false,
            selectedOpen: false,
            selectedEvent: {},
            selectedElement: '',

            deleteDialog: false,	// 追加：初期値は非表示
            deleteID: null,			// 追加：削除Itemのid

            events: [],
            colors: ['blue', 'indigo', 'deep-purple', 'cyan', 'green', 'orange', 'grey darken-1'],
            names: ['Meeting', 'Holiday', 'PTO', 'Travel', 'Event', 'Birthday', 'Conference', 'Party'],
            category: [],
        }),
        filters: {
            moment: function (date) {
                return moment(date).format('MM月DD日 HH:mm');// eslint-disable-line
            }
        },
        computed: {
            cal () {
              return this.ready ? this.$refs.calendar : null
            },
            nowY () {
              return this.cal ? this.cal.timeToY(this.cal.times.now) + 'px' : '-10px'
            },
          },
          mounted () {
            this.ready = true
            this.scrollToTime()
            this.updateTime()
            this.$refs.calendar.checkChange()
          },

        methods: {
          getCurrentTime () {
            return this.cal ? this.cal.times.now.hour * 60 + this.cal.times.now.minute : 0
          },
          scrollToTime () {
            const time = this.getCurrentTime()
            const first = Math.max(0, time - (time % 30) - 30)

            this.cal.scrollToTime(first)
          },
          updateTime () {
            setInterval(() => this.cal.updateTimes(), 60 * 1000)
          },
            pageChange(id){
                console.log(id);
                const page='/schedule/edit/' +id;
                window.location.href = page;
            },
            eventCancel(id){
                console.log(id);
                const page='/schedule/cancel/' +id;
                window.location.href = page;
            },
            showEvent ({ nativeEvent, event }) {
                const open = () => {
                    this.selectedEvent = event
                    this.selectedElement = nativeEvent.target
                    setTimeout(() => this.selectedOpen = true, 10)
                    console.log(this.selectedEvent)
                }
                if (this.selectedOpen) {
                    this.selectedOpen = false
                    setTimeout(open, 10)
                } else {
                    open()
                }
                console.log(this.selectedEvent)

                nativeEvent.stopPropagation()
            },

            getEvents ({ start, end }) {
                const events = []
                const min = new Date(`${start.date}T00:00:00`)
                const max = new Date(`${end.date}T23:59:59`)
                const eventCount = 1;
                console.log("getEvents")
                axios
                    .get('/schedule/getDataJson')
                    .then(function(response){
                        response.data.map(function(data){
                          console.log(response.data)

                            events.push({
                                    id:data[0]["id"],
                                    name: data[0]["name"],
                                    start: new Date(data[3]),
                                    end: new Date(data[4]),
                                    color: data[2]["color"],
                                    timed: true,
                                    category: data[1]["name"],
                                    cource: data[2]["name"],
                                }
                            )
                        });
                    })
                this.events = events
            },
            getEventColor (event) {
                return event.color
            },
            // 削除確認ダイアログ表示を追加
            deleteConfirm(id) {
              this.deleteDialog = true;
              this.deleteID = id;
            },
            deleteItem(id) {
                axios.delete('/schedule/cancel/' + id)
                .then( (res) => {
                  window.location.href = "/schedule/listing"	// 成功したらページを再読み込み。
                })
                .catch( (error) => {
                  console.log(error);
                })
                this.deleteDialog = false;	// 最後に削除確認ダイアログは閉じます。
            },
        },
    })
</script>
</body>
</html>
