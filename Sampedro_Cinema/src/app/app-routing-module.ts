import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { CarteleraComponent } from './cartelera/cartelera';
import { ReservaEntradasComponent } from './reserva-entradas/reserva-entradas';
import { TestApiComponent } from './test-api/test-api';
import { AdminDashboard } from './admin/admin-dashboard/admin-dashboard';
import { AdminPeliculas } from './admin/admin-peliculas/admin-peliculas';
import { AdminSesiones } from './admin/admin-sesiones/admin-sesiones';
import { AdminSalas } from './admin/admin-salas/admin-salas';
import { AdminPeliculaForm } from './admin/admin-pelicula-form/admin-pelicula-form';

const routes: Routes = [
  { path: '', component: CarteleraComponent },
  { path: 'cartelera', component: CarteleraComponent },
  { path: 'reservar/:id', component: ReservaEntradasComponent },
  { path: 'test', component: TestApiComponent },

  { path: 'admin', component: AdminDashboard },
  { path: 'admin/peliculas', component: AdminPeliculas },
  { path: 'admin/peliculas/nueva', component: AdminPeliculaForm },
  { path: 'admin/peliculas/editar/:id', component: AdminPeliculaForm },
  { path: 'admin/sesiones', component: AdminSesiones },
  { path: 'admin/salas', component: AdminSalas },

  { path: '**', redirectTo: '' }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }



