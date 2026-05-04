import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AdminSesiones } from './admin-sesiones';

describe('AdminSesiones', () => {
  let component: AdminSesiones;
  let fixture: ComponentFixture<AdminSesiones>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [AdminSesiones],
    }).compileComponents();

    fixture = TestBed.createComponent(AdminSesiones);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
