<template>
  <input
    type="submit"
    class="btn btn-danger d-block w-100 mb-2"
    value="Eliminar x"
    @click="eliminarReceta"
  />
</template>

<script>
export default {
  props: ["recetaId"],

  methods: {
    eliminarReceta() {
      this.$swal({
        title: "Deseas eliminar esta receta?",
        text: "Una vez eliminada, ya no se puede recuperar",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si",
        cancelButtonText: 'No'
      }).then((result) => {
        if (result.isConfirmed) {
            const params = {
                id: this.recetaId
            }

         //Enviar la éticion al servidor
         axios.post(`/recetas/${this.recetaId}`, {params, _method: 'delete'})
            .then(respuesta => {
                this.$swal({
                    title: 'Receta Eliminada',
                    text: 'Se elimino la receta',
                    icon: 'success'     
                });

                // Eliminar receta del DOM
                this.$le.parentNode,parentNode.parentNode.removeChild(this.$el.parentNode.parentNode);
            })
            .catch(error => { 
                console.log(error)
            })

          
        }
      });
    },
  },
};
</script>`

