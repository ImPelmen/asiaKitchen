<template>
<div id="app">
    <div class= "container">
        <ul class="nav nav-tabs nav-justified">
            <li class="nav-item">
                <a class="nav-link" @click.prevent="setActive('products')" :class="{ active: isActive('products') }" href="#productsTab"> Продукция</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" @click.prevent="setActive('categories')" :class="{ active: isActive('categories') }" href="#categoriesTab"> Категории</a>
            </li>
        </ul>
        <div class="tab-content py-3" id="TabContent">
            <div class="tab-pane fade" :class="{ 'active show': isActive('products') }" id="products">
                <div class="row">
                    <div class="container">
                        <!-- Trigger the modal with a button -->
                        <div class="float-right mb-2">
                            <button type="button" class="btn btn-outline-success btn-sm" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Создать</button>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 50px;">ID</th>
                                    <th>Название</th>
                                    <th>Описание</th>
                                    <th>Цена</th>
                                    <th>Категория</th>
                                    <th>Статус</th>
                                    <th class="text-center" style="width: 100px;">Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="product in products" :key="product.id">
                                    <td>{{ product.id }}</td>
                                    <td>{{ product.title }}</td>
                                    <td>{{ product.description }}</td>
                                    <td>KZT {{ product.price }}</td>
                                    <td>{{ product.categories.map(function(el) { return el.title}).join(', ') }}</td>
                                    <td>
                                        <span class="badge badge-success" v-if="product.is_publish" @click="changePublish(product.id)"><i class="fa fa-check-circle-o fa-2x"></i></span>
                                        <span class="badge badge-danger" v-else="product.is_publish" @click="changePublish(product.id)"><i class="fa fa-times-circle-o fa-2x"></i></span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-primary" v-tooltip="'Редактировать'" @click="setEdited(product.id, 'product')" data-toggle="modal" data-target="#editProductModal">
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
                        <pagination class="ml-3" :data="laravelData" @pagination-change-page="getPagination"></pagination>
                    </div>
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
                                                <option value="0">Выберите категорию</option>
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
                                        <el-upload action="https://jsonplaceholder.typicode.com/posts/" list-type="picture-card" :on-preview="handlePictureCardPreview" :on-change="updateImageList" :auto-upload="false">
                                            <i class="el-icon-plus"></i>
                                        </el-upload>
                                        <el-dialog :visible.sync="dialogVisible">
                                            <img width="100%" :src="dialogImageUrl" alt />
                                        </el-dialog>
                                        <button type="submit" class="close" data-dismiss="modal" @click.prevent="storeProduct">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="editProductModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <form class="m-2">
                                        <div class="form-group">
                                            <label for="editProductTitle">Название продукта</label>
                                            <input type="text" class="form-control" name="title" v-model="editedProduct.title" id="editProductTitle" placeholder="Введите название">
                                        </div>
                                        <div class="form-group">
                                            <label for="editProductCategory">Категория</label>
                                            <select class="form-control custom-select my-1 mr-sm-2" name="category" v-model="editedProduct.category" id="editProductCategory" required>
                                                <option value="0">Выберите категорию</option>
                                                <option v-for="item in categories" :key="item.id" :value="item.id">{{ item.title }}</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="editProductPrice">Цена продукта</label>
                                            <input type="decimal" name="price" v-model="editedProduct.price" class="form-control" id="editProductPrice" placeholder="Введите цену">
                                        </div>
                                        <div class="form-group">
                                            <label for="editProductDescription">Введите описания</label>
                                            <textarea class="form-control" v-model="editedProduct.description" name="description" id="editProductDescription" rows="3" placeholder="Описание"></textarea>
                                        </div>
                                        <el-upload action="https://jsonplaceholder.typicode.com/posts/" list-type="picture-card" :on-preview="handlePictureCardPreview" :on-change="updateImageList" :auto-upload="false">
                                            <i class="el-icon-plus"></i>
                                        </el-upload>
                                        <el-dialog :visible.sync="dialogVisible">
                                            <img width="100%" :src="dialogImageUrl" alt />
                                        </el-dialog>
                                        <button type="submit" class="close" data-dismiss="modal" @click.prevent="storeProduct">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="tab-pane fade" :class="{ 'active show': isActive('categories') }" id="categories">
                <div class="row">
                     <div class="container">
                        <!-- Trigger the modal with a button -->
                        <div class="float-right mb-2">
                            <button type="button" class="btn btn-outline-success btn-sm" data-toggle="modal" data-target="#createCategoryModal"><i class="fa fa-plus"></i> Создать</button>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 50px;">ID</th>
                                    <th>Название</th>
                                    <th class="text-center" style="width: 100px;">Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="category in categories" :key="category.id">
                                    <td>{{ category.id }}</td>
                                    <td>{{ category.title }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-primary" v-tooltip="'Редактировать'" @click="setEdited(category.id, 'category')" data-toggle="modal" data-target="#editCategoryModal">
                                                <i class="fa fa-fw fa-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-primary" v-tooltip="'Удалить'" @click="setDeleted(category.id, 'category')" data-toggle="modal" data-target="#deleteCategoryModal">
                                                <i class="fa fa-fw far fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="createCategoryModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5> Создание категории </h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <form class="m-2">
                                        <div class="form-group">
                                            <label for="createCategory">Название категории</label>
                                            <input type="text" class="form-control" name="title" v-model="newCategory.title" id="createCategory" placeholder="Введите название категории">
                                        </div>
                                        <button type="button" class="btn btn-sm btn-success float-right" data-dismiss="modal" @click.prevent="store(newCategory, 'category')"><i class="far fa-save"></i> Сохранить</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="editCategoryModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5>Редактирование категории #{{ editedCategory.id }}</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <form class="m-2">
                                        <div class="form-group">
                                            <label for="editCategory">Название категории</label>
                                            <input type="text" class="form-control" name="title" v-model="editedCategory.title" id="editCategory" placeholder="Введите название категории">
                                        </div>
                                        <button type="button" class="btn btn-sm btn-success float-right" data-dismiss="modal" @click.prevent="edit(editedCategory, 'category')"><i class="far fa-save"></i> Сохранить</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="deleteCategoryModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5>Удалить категорию?</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <form class="m-2">
                                        <label>Если вы удалите категорию, то не сможете ее вернуть.</label>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-danger float-right" data-dismiss="modal" @click.prevent="remove(deletedCategoryId,'category')">Удалить</button>
                                            <button type="button" class="btn btn-sm btn-secondary float-right" data-dismiss="modal">Отмена</button>
                                        </div>
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

<style>
    .avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    }
    .avatar-uploader .el-upload:hover {
    border-color: #409eff;
    }
    .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 178px;
    height: 178px;
    line-height: 178px;
    text-align: center;
    }
    .avatar {
    width: 178px;
    height: 178px;
    display: block;
}
</style>
<script>
import VTooltip from 'v-tooltip';
Vue.component('pagination', require('laravel-vue-pagination'));
Vue.use(VTooltip);
export default {
    mounted() {
        console.log('Component mounted.')
        let vue = this;
        this.getProducts();
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
            images: [],
            dialogImageUrl: "",
            dialogVisible: false,
            imageList: [],
            status_msg: "",
            status: "",
            isCreatingPost: false,
            activeTab: 'products',
            editedProduct: {},
            editedCategory: {},
            deletedProductId: null,
            deletedCategoryId: null,
            newCategory: {
                title: ''
            }

        }
    },
    methods: {
        storeProduct(e) {
            e.preventDefault();
            let vue = this;
            let config = {
                header : {
                'Content-Type' : 'multipart/form-data'
                }
            }

            this.isCreatingPost = true;
            var formData = new FormData();
            formData.append("title", this.title);
            formData.append("description", this.description);
            formData.append("price", this.price);
            formData.append("category", this.category);
            $.each(this.imageList, function(key, image) {
                formData.append(`images[${key}]`, image);
            });
            console.log(formData);
            axios.post('/admin/product/store', formData, config).then(function (response) {
                // console.log(response.data);
                this.getProducts();
                vue.title = "";
                vue.price = 0;
                vue.description = "";
                vue.category = ""
            });
        },

        getProducts: function() {
            let vue = this;
            axios.get('/admin/product/getproduct').then(function (response) {
                vue.products = response.data.products.data;
                vue.categories = response.data.category;
                vue.getPagination();
            });
        },

        getPagination(page = 1) {
            console.log('poshel nahui i paginaziu zabery');
            let vue = this;
            axios.get('/admin/product/getproduct?page=' + page)
                .then(function(response) {
                    vue.laravelData = response.data.products;
                    vue.products = response.data.products.data;
                });
        },

        changePublish(id) {
            let vue = this;
            axios.get('/admin/product/' + id + '/publish').then(function (response) {
                for (let product in vue.products) {
                    if (vue.products[product].id == response.data.product.id) {
                        vue.$set(vue.products, product, response.data.product)
                    }
                }
            });
        },

        updateImageList(file) {
            this.imageList.push(file.raw);
        },

        handlePictureCardPreview(file) {
            this.dialogImageUrl = file.url;
            this.imageList.push(file);
            this.dialogVisible = true;
        },

        showNotification(message) {
            //fixed spelling mistake
            this.status_msg = message;
            setTimeout(() => {
                this.status_msg = "";
            }, 3000);
        },

        isActive (menuTab) {
            return this.activeTab === menuTab;
        },

        setActive (menuTab) {
            this.activeTab = menuTab
        },

        setEdited: function(id, item) {
            if (item == 'product') {
                for (let product of this.products) {
                    if (id == product.id) {
                        this.editedProduct = product;
                        console.log(this.editedProduct);
                    }
                }
            } else if (item == 'category') {
                for (let category of this.categories) {
                    if (id == category.id) {
                        this.editedCategory = category;
                    }
                }
            }
        },

        setDeleted: function(id, item) {
            if (item == 'product') {
                for (let product of this.products) {
                    if (id == product.id) {
                        this.deletedProductId = id;
                    }
                }
            } else if (item == 'category') {
                for (let category of this.categories) {
                    if (id == category.id) {
                        this.deletedCategoryId = id;
                    }
                }
            }
        },

        edit: function(edited, item) {
            let vue = this;
            if (item == 'product') {
                axios.post('/admin/products/edit', {
                    product: this.editedProduct
                }).then(function(response) {
                    console.log(response.data.products);
                    vue.products = response.data.products;
                });
            } else if (item == 'category') {
                axios.post('/admin/categories/edit', {
                    category: this.editedCategory
                }).then(function(response) {
                    vue.categories = response.data.categories;
                });
            }
        },

        remove: function(id, item) {
            let vue = this;
            if (item == 'product') {
                axios.post('/admin/products/remove', {
                    id: this.deletedProductId                    
                }).then(function(response) {
                    vue.products = response.data.products;
                });
            } else if (item == 'category') {
                axios.post('/admin/categories/remove', {
                    id: this.deletedCategoryId
                }).then(function(response) {
                    vue.categories = response.data.categories;
                }) 
            }
        },

        store: function(newItem, item) {
            let vue = this;
            if (item == 'product') {
                //
            } else if (item == 'category') {
                console.log(this.newCategory);
                axios.post('/admin/categories/store', {
                    category: newItem 
                }).then(function(response) {
                    vue.categories = response.data.categories;
                    vue.newCategory = {
                        title: ''
                    };
                });
            }
        }
    }
}
</script>
