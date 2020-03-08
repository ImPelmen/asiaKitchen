<template>
<div id="app" class="container mt-5">
    <div class="block">
        <ul id="productTab" class="nav nav-tabs nav-tabs-alt js-tabs-enabled" data-toggle="tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" href="#btabs-alt-static-home"> Управление продукцией</a>
            </li>
        </ul>
        <div class="block-content tab-content">
            <div class="tab-pane active" id="btabs-alt-static-home" role="tabpanel">
                <div class="container">
                    <div class="row">
                        <div class="container">
                            <h2>Все товары</h2>
                            <!-- Trigger the modal with a button -->
                            <div class="float-right mb-2">
                                <button type="button" class="btn btn-outline-success btn-sm" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Создать</button>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Название</th>
                                        <th>Описание</th>
                                        <th>Цена</th>
                                        <th>Категория</th>
                                        <th>Статус</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="product in products" :key="product.id">
                                        <td>{{ product.id }}</td>
                                        <td>{{ product.title }}</td>
                                        <td>{{ product.description }}</td>
                                        <td>KZT {{ product.price }}</td>
                                        <td>{{ product.id }}</td>
                                        <td>
                                            <span class="badge badge-success" v-if="product.is_publish" @click="changePublish(product.id)"><i class="fa fa-check-circle-o fa-2x"></i></span>
                                            <span class="badge badge-danger" v-else="product.is_publish"  @click="changePublish(product.id)"><i class="fa fa-times-circle-o fa-2x"></i></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <pagination class="ml-3" :data="laravelData" @pagination-change-page="getResults"></pagination>
                        <!-- Modal -->
                        <div id="myModal" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <form class="m-2">
                                            <div class="form-group">
                                                <label for="exampleFormControlInput1">Название продукта</label>
                                                <input type="text" class="form-control" name="title" v-model="title" id="exampleFormControlInput1" placeholder="Введите название">
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleFormControlSelect1">Категория</label>
                                                <select class="form-control custom-select my-1 mr-sm-2" name="category" v-model="category" id="exampleFormControlSelect1" required>
                                                    <option v-for="item in categories" :key="item.id" :value="item.id">{{ item.title }}</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleFormControlInput1">Цена продукта</label>
                                                <input type="decimal" name="price" v-model="price" class="form-control" id="exampleFormControlInput1" placeholder="Цена название">
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleFormControlTextarea1">Введите описания</label>
                                                <textarea class="form-control" v-model="description" name="description" id="exampleFormControlTextarea1" rows="3" placeholder="Описание"></textarea>
                                            </div>
                                            <button type="submit" class="close" data-dismiss="modal" @click.prevent="storeProduct">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
