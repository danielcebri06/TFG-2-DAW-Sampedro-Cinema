import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { CarteleraComponent } from './cartelera/cartelera';
import { ReservaEntradasComponent } from './reserva-entradas/reserva-entradas';

const routes: Routes = [
  { path: '', component: CarteleraComponent }, 
  { path: 'reservar/:id', component: ReservaEntradasComponent },
  { path: '**', redirectTo: '' }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }