import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { HttpClientModule } from '@angular/common/http';
import { AppRoutingModule } from './app-routing-module';
import { App } from './app';
import { FormsModule } from '@angular/forms';

import { CarteleraComponent } from './cartelera/cartelera';
import { ReservaEntradasComponent } from './reserva-entradas/reserva-entradas';
import { TestApiComponent } from './test-api/test-api';
import { AdminDashboard } from './admin/admin-dashboard/admin-dashboard';
import { AdminPeliculas } from './admin/admin-peliculas/admin-peliculas';
import { AdminSesiones } from './admin/admin-sesiones/admin-sesiones';
import { AdminSalas } from './admin/admin-salas/admin-salas';
import { AdminPeliculaForm } from './admin/admin-pelicula-form/admin-pelicula-form';

@NgModule({
  declarations: [
    App,
    CarteleraComponent,
    ReservaEntradasComponent,
    TestApiComponent,
    AdminDashboard,
    AdminPeliculas,
    AdminSesiones,
    AdminSalas,
    AdminPeliculaForm,
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule, // Permite las peticiones a la API
    FormsModule
  ],
  bootstrap: [App],
})
export class AppModule {}
