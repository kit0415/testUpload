radius = float(raw_input("Enter Radius of Sphere: "))
diameter = radius * 2.00
circumference = 2.00 * 3.1416* radius
surface_area = 4.00 * 3.1416* radius**2
volume = (4/3)* 3.1416 *radius**3

print("Diameter is: %.2f\nCircumference is: %.2f\nSurface Area is: %.2f\nVolumn is: %.2f" %(diameter,circumference,surface_area,volume))
