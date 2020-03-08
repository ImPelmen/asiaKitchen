<template>
<div id="app" class="container mt-5">
    <div class="block">
        <div>
  <b-card no-body>
    <b-tabs pills card>
      <b-tab title="Tab 1" active><b-card-text>Tab contents 1</b-card-text></b-tab>
      <b-tab title="Tab 2"><b-card-text>Tab contents 2</b-card-text></b-tab>
    </b-tabs>
  </b-card>
</div>
    </div>
</div>
</template>

<script>
Vue.component('pagination', require('laravel-vue-pagination'));
export default {
    mounted() {
        console.log('Component mounted.')
        let vue = this;
        axios.get('/admin/product/getproduct').then(function (response) {
            vue.products = response.data.products.data;
            vue.categories = response.data.category;
            console.log(vue.categories);
        });

        this.getResults();
    },
    data() {
        return {
            products: [],
            categories: [],
            title: "",
            price: null,
            category: null,
            description: "",
            laravelData: {},
        }
    },
    methods: {
        storeProduct() {
            let vue = this;
            axios.post('/admin/product/store', {
                title: this.title,
                price: this.price,
                description: this.description,
                category: this.category
            }).then(function (response) {
                console.log(response.data);
                vue.products = response.data.products.data;
                vue.title = "";
                vue.price = 0;
                vue.description = "";
                vue.category = ""
            });
        },
        getResults(page = 1) {
			axios.get('/admin/product/getproduct?page=' + page)
				.then(response => {
					this.laravelData = response.data.products;
                    this.products = response.data.products.data;
				});
		},
        changePublish(id) {
            let vue = this;
            axios.get('/admin/product/' + id + '/publish').then(function(response) {
                for(let product in vue.products) {
                    if(vue.products[product].id == response.data.product.id) {
                        vue.$set(vue.products,product , response.data.product)
                    }
                }
            });
        }
    }
}
</script>
