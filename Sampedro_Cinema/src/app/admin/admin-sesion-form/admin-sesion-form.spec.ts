import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AdminSesionForm } from './admin-sesion-form';

describe('AdminSesionForm', () => {
  let component: AdminSesionForm;
  let fixture: ComponentFixture<AdminSesionForm>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [AdminSesionForm],
    }).compileComponents();

    fixture = TestBed.createComponent(AdminSesionForm);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
