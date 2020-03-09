<template>
    <div class="header-search">
        <form>
            <select class="input-select">
                <option value="0">Все категорий</option>
                <option v-for="category of categories" :key="category.id" :value="category.id">{{ category.title }}</option>
            </select>
            <input class="input" placeholder="Search here">
            <button class="search-btn">Search</button>
        </form>
    </div>
</template>

<script>
    export default {
        mounted() {
            let vue = this;
            axios.get('api/get/category').then(function(response) {
                vue.categories = response.data;
            });
        },
        data() {
            return {
                categories: []
            }
        }
    }
</script>
