<template>
    <v-select
        class="px-2 py-1 border border-gray-200 rounded-none shadow-none  bg-gray-50"
        :model-value="modelValue"
        @update:modelValue="onSelect"
        :multiple="multiple"
        :options="paginatedObject.data"
        :filterable="false"
        :clear-on-select="true"
        :reduce="reduce"
        :placeholder="placeholder"
        :label="label"
        @open="onOpen"
        @close="onClose"
        @search="fetchResults"
    >
        <template #search="{ attributes, events }">
            <input
                style="border-right: none !important"
                class="vs__search"
                v-bind="attributes"
                v-on="events"
            />
        </template>
        <template #list-footer v-if="hasNextPage">
            <li ref="infiniteSelectLoad" class="loader">
                Loading more options...
            </li>
        </template>
    </v-select>
</template>

<script>
import vSelect from "vue-select/src/index";
import { defineComponent } from "vue";
export default defineComponent({
    name: "InfiniteSelect",
    components: {
        "v-select": vSelect,
    },
    emits: ["update:modelValue"],
    model: {
        prop: "modelValue",
        event: "update:modelValue",
    },
    props: {
        apiUrl: {
            required: true,
            type: String,
        },
        queryParams: {
            required: false,
            default: () => {
                return {};
            },
        },
        perPage: {
            required: false,
            default: 15,
        },
        multiple: {
            type: Boolean,
            default: false,
        },
        modelValue: {
            default: null,
        },
        label: {
            required: true,
            type: String,
        },
        reduce: {
            type: Function,
        },
        placeholder: String,
    },
    data: () => ({
        observer: null,
        paginatedObject: {
            data: [],
        },
        searchQuery: null,
    }),
    mounted() {
        this.paginatedObject.per_page = parseInt(this.perPage) || 15;
        this.selected = this.initValue;
        this.observer = new IntersectionObserver(this.infiniteScroll);
    },
    methods: {
        async onOpen() {
            if (this.hasNextPage) {
                await this.$nextTick();
                this.observer.observe(this.$refs.infiniteSelectLoad);
            }
        },
        onClose() {
            this.observer.disconnect();
            this.searchQuery = null;
        },
        onSelect(value) {
            this.$emit("update:modelValue", value);
        },
        async fetchResults(query, loading, more = false) {
            const vm = this;
            if (query) {
                vm.searchQuery = query;
            }
            let params = {};
            if (vm.paginatedObject.current_page && more) {
                if (!vm.paginatedObject.next_page_url) {
                    return false;
                }
                params.page = vm.paginatedObject.current_page + 1;
                params.per_page = vm.paginatedObject.per_page;
            }
            if (vm.searchQuery) {
                params.search = vm.searchQuery;
            }
            params = { ...params, ...vm.queryParams };
            return new Promise((resolve, reject) => {
                const vm = this;
                loading = true;
                axios
                    .get(vm.apiUrl, {
                        params: params,
                    })
                    .then((res) => {
                        // process and store results.
                        const bak = vm.paginatedObject.data;
                        vm.paginatedObject = res.data.payload;
                        if (more) {
                            vm.paginatedObject.data = [
                                ...bak,
                                ...res.data.payload.data,
                            ];
                        }
                        resolve(res);
                    })
                    .catch((err) => {
                        // reset Object, report error.
                        reject(err);
                    })
                    .finally((res) => {
                        loading = false;
                    });
            });
        },
        async infiniteScroll([{ isIntersecting, target }]) {
            if (isIntersecting) {
                const ul = target.offsetParent;
                const scrollTop = target.offsetParent.scrollTop;
                await this.fetchResults(null, true, true);
                ul.scrollTop = scrollTop;
            }
        },
    },
    computed: {
        hasNextPage() {
            let vm = this;
            return (
                (vm.paginatedObject.current_page &&
                    vm.paginatedObject.next_page_url) ||
                (!vm.paginatedObject.data.length &&
                    !vm.paginatedObject.current_page)
            );
        },
    },
    watch: {},
});
</script>

<style lang="scss">
@import "vue-select/src/scss/vue-select.scss";

.loader {
    text-align: center;
    color: #bbbbbb;
}
.vs__dropdown-toggle {
    border: none !important;
}
</style>
