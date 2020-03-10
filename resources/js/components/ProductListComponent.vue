<template>
<div id="app" class="container mt-5">
    <div class="block">
        <ul class="nav nav-tabs nav-tabs-alt js-tabs-enabled">
            <li class="nav-item">
                <a class="nav-link" @click.prevent="setActive('production')" :class="{ active: isActive('production') }" href="#production"> Продукция</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" @click.prevent="setActive('categories')" :class="{ active: isActive('categories') }" href="#categories"> Категории</a>
            </li>
        </ul>
        <div class="tab-content py-3" id="myTabContent">
            <div class="tab-pane fade" :class="{ 'active show': isActive('production') }" id="home"> 
                <div class="container">
                    <div class="row">
                        <div class="container">
                            <!-- Trigger the modal with a button -->
                            <div class="float-right mb-2 mt-2">
                                <button type="button" class="btn btn-outline-success btn-sm" data-toggle="modal" data-target="#createProductModal"><i class="fa fa-plus"></i> Создать</button>
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
                                        <th>Действия</th>
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
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" v-tooltip="'Редактировать'" @click="setEdited(product.id, 'product')">
                                                    <i class="fa fa-fw fa-pencil-alt"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-primary" v-tooltip="'Удалить'" @click="setDeleted(product.id, 'product')" data-toggle="modal" data-target="#deleteProductModal">
                                                    <i class="fa fa-fw far fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <pagination class="ml-3" :data="laravelData" @pagination-change-page="getResults"></pagination>
                        <!-- Modal -->
                        <div id="createProductModal" class="modal fade" role="dialog">
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
                                            <button type="submit" class="btn btn-sm btn-success float-right" data-dismiss="modal" @click.prevent="storeProduct"><i class="far fa-save"></i> Сохранить</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="deleteProductModal" tabindex="-1" role="dialog" aria-labelledby="modal-block-fadein" style="display: none;" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="block block-themed block-transparent mb-0">
                        <div class="block-header bg-primary-dark">
                            <h3 class="block-title">Удалить продукт?</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content font-size-sm">
                            <p>Если Вы удалите продукт, то Вы не сможете вернуть его обратно.</p>
                        </div>
                        <form class="form-horizontal" id="delete">
                            <div class="block-content block-content-full text-right border-top">
                                <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Отмена</button>
                                <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" @click="remove('product')">Удалить</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" :class="{ 'active show': isActive('categories') }" id="profile"> 
            <div class="container">
                <div class="row">
                    <div class="container">
                        <!-- Trigger the modal with a button -->
                        <div class="float-right mb-2 mt-2">
                            <button type="button" class="btn btn-outline-success btn-sm" data-toggle="modal" data-target="#createCategoryModal"><i class="fa fa-plus"></i> Создать</button>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Название</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="category of categories" :key="category.id">
                                    <td>{{ category.id }}</td>
                                    <td>{{ category.title }}</td>
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
                    <div id="createCategoryModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <form class="m-2">
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Название категории</label>
                                            <input type="text" class="form-control" name="title" v-model="newCategory.title" id="exampleFormControlInput1" placeholder="Введите название">
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-success float-right" data-dismiss="modal" @click.prevent="store(newCategory, 'category')"><i class="far fa-save"></i> Сохранить</button>
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
            activeTab: 'production',
            editedProduct: {},
            editedCategory: {},
            newCategory: {
                id: null,
                title: ''
            },
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
        },
        
        isActive: function(menuItem) {
            return this.activeTab == menuItem;
        },

        setActive: function(menuItem) {
            this.activeTab = menuItem;
        },

        setEdited: function(id, item) {
            if (item == 'product') {
                for (let product of this.products) {
                    if (product.id == id) {
                        this.editedProduct = product;
                    }
                }
            } else if (item == 'category') {
                for (let category of this.categories) {
                    if (category.id == id) {
                        this.editedCategory = category;
                    }
                }
            }
        },

        setDeleted: function(id, item) {
            if (item == 'product') {
                for (let product of this.products) {
                    if (product.id == id) {
                        this.deletedProductId = id;
                    }
                }
            } else if (item = 'category') {
                for (let category of this.categories) {
                    if (category.id == id) {
                        this.deletedCategoryId = id;
                    }
                }
            }
        },

        remove: function(item) {
            // if (item == 'product') {
            //     axios.post('/')
            // }
        },

        store: function(newThing, item) {
            if (item == 'product') {
                //
            } else if (item == 'category') {
                console.log(item);
                console.log(this.newCategory);
                axios.post('/admin/category/store', {
                    category: newThing
                }).then(function(response) {
                    console.log(response.data.categories);
                });
            }
        }
    }
}
</script>
