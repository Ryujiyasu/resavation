<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
</head>
<body>
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
                        :categories="categories"
                        :events="events"
                        :event-overlap-mode="mode"
                        :event-overlap-threshold="30"
                        :event-color="getEventColor"
                        @change="getEvents"
                        @click:event="showEvent"
                    ></v-calendar>
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
                                <span v-html="selectedEvent.start"></span>
                                <span v-html="selectedEvent.end"></span>
                            </v-card-text>
                            <v-card-actions>
                                <v-btn
                                    text
                                    color="secondary"
                                    @click="selectedOpen = false"
                                >
                                    Cancel
                                </v-btn>
                            </v-card-actions>
                        </v-card>

                    </v-menu>
                </v-sheet>
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
            test:"",
            type: 'month',
            types: ['month', 'week', 'day', '4day','category'],
            mode: 'stack',
            modes: ['stack', 'column'],
            categories: ['John Smith', 'Tori Walker'],
            weekday: [0, 1, 2, 3, 4, 5, 6],
            weekdays: [
                { text: 'Sun - Sat', value: [0, 1, 2, 3, 4, 5, 6] },
                { text: 'Mon - Sun', value: [1, 2, 3, 4, 5, 6, 0] },
                { text: 'Mon - Fri', value: [1, 2, 3, 4, 5] },
                { text: 'Mon, Wed, Fri', value: [1, 3, 5] },
            ],
            value: '',
            selectedOpen: false,
            selectedEvent: {},

            events: [],
            colors: ['blue', 'indigo', 'deep-purple', 'cyan', 'green', 'orange', 'grey darken-1'],
            names: ['Meeting', 'Holiday', 'PTO', 'Travel', 'Event', 'Birthday', 'Conference', 'Party'],
        }),
        methods: {
            pageChange(id){
                console.log(id);
                const page='/schedule/edit/' +id;
                window.location.href = page;


            },
            showEvent ({ nativeEvent, event }) {
                const open = () => {
                    this.selectedEvent = event
                    this.selectedElement = nativeEvent.target
                    setTimeout(() => this.selectedOpen = true, 10)
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
                            console.log(data[3]);
                            console.log(data[4]);

                            events.push({
                                    id:data[0]["id"],
                                    name: data[0]["name"],
                                    start: new Date(data[3]),
                                    end: new Date(data[4]),
                                    color: data[1]["color"],
                                    timed: true,
                                    category: 'A',
                                }
                            )
                            events.push({
                                    id:data[0]["id"],
                                    name: data[0]["name"],
                                    start: new Date(data[3]),
                                    end: new Date(data[4]),
                                    color: data[1]["color"],
                                    timed: true,
                                    category: 'B',
                                }
                            )

                        });
                    })
                this.events = events
            },
            getEventColor (event) {
                return event.color
            },
            rnd (a, b) {
                return Math.floor((b - a + 1) * Math.random()) + a
            },
        },
    })
</script>
</body>
</html>

