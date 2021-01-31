<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
    <link href="../css/reception.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
</head>
<body>
        <div id="app">
  <header class="grovalNavigation">
    <p>グローバルナビゲーション</p>

  </header>
  <main>
    <div class="localNavigation">
      <p>ローカルナビゲーション</p>
      <v-card
         height="400"
         width="256"
         class="mx-auto"
       >
         <v-navigation-drawer permanent>
           <v-list-item>
             <v-list-item-content>
               <v-list-item-title class="title">
                 Application
               </v-list-item-title>
               <v-list-item-subtitle>
                 subtext
               </v-list-item-subtitle>
             </v-list-item-content>
           </v-list-item>

           <v-divider></v-divider>

           <v-list
             dense
             nav
           >
             <v-list-item
               v-for="item in items"
               :key="item.title"
               link
             >
               <v-list-item-icon>
                 <v-icon>@{{ item.icon }}</v-icon>
               </v-list-item-icon>

               <v-list-item-content>
                 <v-list-item-title>@{{ item.title }}</v-list-item-title>
               </v-list-item-content>
             </v-list-item>
           </v-list>
         </v-navigation-drawer>
       </v-card>
    </div>


    <div class="content">
      <p>メインコンテンツ</p>
        <v-app id="inspire">
          <v-data-table
            :headers="headers"
            :items="patients"
            sort-by=""
            class="elevation-1"
          >
            <template v-slot:top>
              <v-toolbar
                flat
              >
                <v-toolbar-title>My CRUD</v-toolbar-title>
                <v-divider
                  class="mx-4"
                  inset
                  vertical
                ></v-divider>
                <v-spacer></v-spacer>
                <v-dialog
                  v-model="dialog"
                  max-width="500px"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-btn
                      color="primary"
                      dark
                      class="mb-2"
                      v-bind="attrs"
                      v-on="on"
                    >
                      新規登録
                    </v-btn>
                  </template>
                  <v-card>
                    <v-card-title>
                      <span class="headline">card title</span>
                    </v-card-title>

                    <v-card-text>
                      <v-container>
                        <v-row>
                          <v-col
                            cols="12"
                            sm="6"
                            md="4"
                          >
                            <v-text-field
                              v-model="editedItem.name"
                              label="患者名"
                            ></v-text-field>
                          </v-col>
                          <v-col
                            cols="12"
                            sm="6"
                            md="4"
                          >
                            <v-text-field
                              v-model="editedItem.calories"
                              label="Calories"
                            ></v-text-field>
                          </v-col>
                          <v-col
                            cols="12"
                            sm="6"
                            md="4"
                          >
                            <v-text-field
                              v-model="editedItem.fat"
                              label="Fat (g)"
                            ></v-text-field>
                          </v-col>
                          <v-col
                            cols="12"
                            sm="6"
                            md="4"
                          >
                            <v-text-field
                              v-model="editedItem.carbs"
                              label="Carbs (g)"
                            ></v-text-field>
                          </v-col>
                          <v-col
                            cols="12"
                            sm="6"
                            md="4"
                          >
                            <v-text-field
                              v-model="editedItem.protein"
                              label="Protein (g)"
                            ></v-text-field>
                          </v-col>
                        </v-row>
                      </v-container>
                    </v-card-text>

                    <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-btn
                        color="error"
                        dark
                        @click="close"
                      >
                        キャンセル
                      </v-btn>
                      <v-btn
                        color="primary"
                        dark
                        @click="save"
                      >
                        登録
                      </v-btn>
                    </v-card-actions>
                  </v-card>
                </v-dialog>
                <v-dialog v-model="dialogDelete" max-width="500px">
                  <v-card>
                    <v-card-title class="headline">削除してもよろしいですか？</v-card-title>
                    <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-btn color="error" dark @click="closeDelete">キャンセル</v-btn>
                      <v-btn color="primary" dark @click="deleteItemConfirm">OK</v-btn>
                      <v-spacer></v-spacer>
                    </v-card-actions>
                  </v-card>
                </v-dialog>
              </v-toolbar>
            </template>
            <template v-slot:item.actions="{ item }">
              <v-icon
                small
                class="mr-2"
                @click="editItem(item)"
              >
                mdi-pencil
              </v-icon>
              <v-icon
                small
                @click="deleteItem(item)"
              >
                mdi-delete
              </v-icon>
            </template>
            <template v-slot:no-data>
              <v-btn
                color="primary"
                @click="initialize"
              >
                Reset
              </v-btn>
            </template>
          </v-data-table>
        </v-app>
      </div>




    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
  <script>
  new Vue({
el: '#app',
vuetify: new Vuetify(),
data: () => ({
  dialog: false,
  dialogDelete: false,
  headers: [
    {
      text: 'Dessert (100g serving)',
      align: 'start',
      sortable: false,
      value: 'name',
    },
    { text: 'Calories', value: 'calories' },
    { text: 'Fat (g)', value: 'fat' },
    { text: 'Carbs (g)', value: 'carbs' },
    { text: 'Protein (g)', value: 'protein' },
    { text: 'Actions', value: 'actions', sortable: false },
  ],
  patients: [],
  editedIndex: -1,
  editedItem: {
    name: '',
    calories: 0,
    fat: 0,
    carbs: 0,
    protein: 0,
  },
  defaultItem: {
    name: '',
    calories: 0,
    fat: 0,
    carbs: 0,
    protein: 0,
  },

    items: [
      { title: 'Dashboard', icon: 'mdi-view-dashboard' },
      { title: 'Photos', icon: 'mdi-image' },
      { title: 'About', icon: 'mdi-help-box' },
    ],
    right: null,
}

),

computed: {
  formTitle () {
    return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
  },
},

watch: {
  dialog (val) {
    val || this.close()
  },
  dialogDelete (val) {
    val || this.closeDelete()
  },
},

created () {
  this.initialize()
},

methods: {
  initialize () {
    this.patients = [
      {
        name: 'Frozen Yogurt',
        calories: 159,
        fat: 6.0,
        carbs: 24,
        protein: 4.0,
      },
    ]
  },

  editItem (item) {
    this.editedIndex = this.patients.indexOf(item)
    this.editedItem = Object.assign({}, item)
    this.dialog = true
  },

  deleteItem (item) {
    this.editedIndex = this.patients.indexOf(item)
    this.editedItem = Object.assign({}, item)
    this.dialogDelete = true
  },

  deleteItemConfirm () {
    this.patients.splice(this.editedIndex, 1)
    this.closeDelete()
  },

  close () {
    this.dialog = false
    this.$nextTick(() => {
      this.editedItem = Object.assign({}, this.defaultItem)
      this.editedIndex = -1
    })
  },

  closeDelete () {
    this.dialogDelete = false
    this.$nextTick(() => {
      this.editedItem = Object.assign({}, this.defaultItem)
      this.editedIndex = -1
    })
  },

  save () {
    if (this.editedIndex > -1) {
      Object.assign(this.patients[this.editedIndex], this.editedItem)
    } else {
      this.patients.push(this.editedItem)
    }
    this.close()
  },
},
})
  </script>
</body>
</html>
