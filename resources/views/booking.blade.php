<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">

    <title>Calendar</title>

</head>
<body>


  <div id="app">
    <v-app id="inspire">
      </v-row>
      <v-form
        ref="form"
        v-model="valid"
        lazy-validation
      >
        <v-text-field
          v-model="name"
          :counter="10"
          :rules="nameRules"
          label="お名前"
          required
        ></v-text-field>

        <v-text-field
          v-model="email"
          :rules="emailRules"
          label="Eメールアドレス"
          required
        ></v-text-field>

        <v-text-field
          v-model="tel"
          :counter="11"
          :rules="telRules"
          label="電話番号"
          required
        ></v-text-field>

        <v-menu
          ref="menu"
          v-model="menu"
          :close-on-content-click="false"
          :return-value.sync="date"
          transition="scale-transition"
          offset-y
          min-width="290px"
        >
        <v-date-picker
          v-model="date"
          no-title
          scrollable
        >
    <v-spacer></v-spacer>
  </v-date-picker>
</v-menu>
  <v-spacer></v-spacer>
    <v-menu
      v-model="menu2"
      :close-on-content-click="false"
      :nudge-right="40"
      transition="scale-transition"
      offset-y
      min-width="290px"
    >
      <template v-slot:activator="{ on, attrs }">
        <v-text-field
          v-model="date"
          label="Picker without buttons"
          prepend-icon="mdi-calendar"
          readonly
          v-bind="attrs"
          v-on="on"
        ></v-text-field>
      </template>
      <v-date-picker
        v-model="date"
        @input="menu2 = false"
      ></v-date-picker>
    </v-menu>
      <v-select
          v-model="staffSelect"
          :items="staffsName"
          :rules="[v => !!v || 'スタッフを選択してください']"
          label="スタッフ"
          item-text="name"
          item-value="id"
          required
          @change="staffChoiced"
        ></v-select>


        <v-select
          v-model="timeSelect"
          :items="timeItems"
          :rules="[v => !!v || '時間を選択してください']"
          label="時間"
          item-text="name"
          item-value="id"
          required
        ></v-select>

        <v-btn
          :disabled="!valid"
          color="success"
          class="mr-4"
          @click="validate"
        >
          Validate
        </v-btn>

        <v-btn
          color="error"
          class="mr-4"
          @click="reset"
        >
          Reset Form
        </v-btn>

        <v-btn
          color="warning"
          @click="resetValidation"
        >
          Reset Validation
        </v-btn>
        <v-btn
          class="mr-4"
          @click="submit"
        >
          submit
        </v-btn>
      </v-form>
    </v-row>
    </v-app>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script>
  new Vue({
    el: '#app',
    vuetify: new Vuetify(),
    data: () => ({

      date: new Date().toISOString().substr(0, 10),
      menu: false,
      modal: false,
      menu2: false,

      valid: true,
      name: '',
      nameRules: [
        v => !!v || 'お名前を入力してください',
        v => (v && v.length <= 10) || 'Name must be less than 10 characters',
      ],
      email: '',
      emailRules: [
        v => !!v || 'Eメールアドレスを入力してください',
        v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
      ],
      tel: '',
      telRules: [
        v => !!v || '電話番号を入力してください',
        v => (v && v.length <= 11) || 'E-mail must be valid',
      ],
      staffSelect: null,
      staffsName: [
          {name:'Item 1',id:1},
          {name:'Item 2',id:2},
          {name:'Item 3',id:3},
          {name:'Item 4',id:4},
      ],
      timeSelect: null,
      timeItems: [{name:'Item 1',id:1}],
      checkbox: false,
    }),

    methods: {
      validate () {
        this.$refs.form.validate()
      },
      reset () {
        this.$refs.form.reset()
      },
      resetValidation () {
        this.$refs.form.resetValidation()
      },
      submit () {
        this.$refs.form.validate()
      },
      staffChoiced () {
        var self = axios.get('/schedule/getData?date=2020-09-19&staff=1')
        console.log(self);
      },
    },
    // watch: {
    //     select:{
    //         handler: function ($post_select) {
    //             console.log($post_select);
    //
    //
    //             //.then(response => (this.info = response));
    //                 // .then(function (res) {
    //                 console.log(this.response);
    //                     this.timeItems= [{name:'Item 5',id:5},
    //                                     {name:'Item 2',id:2},
    //                                     {name:'Item 3',id:3},
    //                                     {name:'Item 4',id:4},];
    //                 // });
    //         },
    //
    //     },
    //     computed: {
    //         eventedAction: function() {
    //             console.log(this.items); //②ここではスコープが切れてlength 0
    //             let list = this.items.slice();
    //
    //             return list;
    //         }
    //     }
    //
    //
    // },
  })
</script>


</body>
<footer style="text-align:center">Send Grid</footer>

</html>
