<template>
    <nav v-if="meta" class="d-flex justify-content-around">
        <ul class="pagination">
            <li v-for="(link, index) in meta.links" class="page-item">
                <button v-if="index === 0" class="page-link" @click="$emit('pageChange', link.url)" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </button>
                <button v-else-if="index === meta.links.length-1" class="page-link" @click="$emit('pageChange', link.url)" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </button>

                <button v-else class="page-link" @click="$emit('pageChange', link.url)">{{ link.label }}</button>
            </li>
        </ul>
        <div class="d-block p-2">
            Showing <span class="font-medium text-slate-600">{{ meta.from }}</span> to <span class="font-medium text-slate-600">{{ meta.to }}</span> of <span class="font-medium text-slate-600">{{ meta.total }}</span> results
        </div>
    </nav>
</template>

<script>
export default {
    props: ['meta'],
    name: 'Pagination',
}
</script>