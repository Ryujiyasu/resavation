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
    <v-app>
        <v-container>
            <v-card>
                <v-card-title>
                    スケジュール
                    <v-spacer></v-spacer>
                    <v-text-field
                        v-model="search"
                        append-icon="mdi-magnify"
                        label="Search"
                        single-line
                        hide-details
                    ></v-text-field>
                    <v-text-field
                        v-model="search2"
                    ></v-text-field>
                </v-card-title>
                    <v-data-table
                        :search="search"
                        :headers="headers"
                        :items="schedules"
                        :sort-by="['日付', '時間','メール','電話','スタッフ','コース']"
                        :sort-desc="[false, true]"
                        multi-sort
                        class="elevation-1"
                    ></v-data-table>
            </v-card>
        </v-container>
    </v-app>
</div>

<script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
<script>
    new Vue({
        el: '#app',
        vuetify: new Vuetify(),
        data () {
            return {
                search: '',
                searc2h: '',
                headers: [
                    {
                        text: 'id',
                        align: 'start',
                        sortable: true,
                        value: 'id',
                    },
                    { text: '日付', value: '日付' },
                    { text: '時間', value: '時間' },
                    { text: 'メール', value: 'メール' },
                    { text: '電話', value: '電話' },
                    { text: 'スタッフ', value: 'スタッフ' },
                    { text: 'コース', value: 'コース' },
                ],
                schedules: [
                    @foreach ($schedules as $schedule)
                        {
                            id: '{{$schedule->id}}',
                            日付: '{{$schedule->schedule_date}}',
                            時間: '{{$schedule->name}}',
                            メール: '{{$schedule->email}}',
                            電話: '{{$schedule->tel}}',
                            スタッフ: '{{$schedule->Staff->name}}',
                            コース: '{{$schedule->Cource->name}}'
                        },
                    @endforeach
                ],
            }
        },
    })
</script>
</body>
</html>

