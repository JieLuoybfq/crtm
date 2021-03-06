#==============================================================================
#
# Makefile for AtmProfile2Atmosphere program
#
#==============================================================================

# Define macros
include $(CRTM_SOURCE_ROOT)/make.macros

# This makefile
MAKE_FILE = Makefile

# Executable file
EXE_FILE = AtmProfile2Atmosphere

# Source files to link
SRC_FILES = Type_Kinds.f90 \
            File_Utility.f90 \
            Message_Handler.f90 \
            Compare_Float_Numbers.f90 \
            Endian_Utility.f90 \
            Binary_File_Utility.f90 \
            String_Utility.f90 \
            Fundamental_Constants.f90 \
            Profile_Utility_Parameters.f90 \
            CRTM_Parameters.f90 \
            CRTM_Atmosphere_Define.f90 \
            CRTM_Cloud_Define.f90 \
            CRTM_Aerosol_Define.f90 \
            AtmProfile_Parameters.f90 \
            AtmProfile_Define.f90 \
            AtmProfile_netCDF_IO.f90
INC_FILES = AtmProfile_netCDF_IO.inc

# Obj files used in link phase
OBJ_FILES = ${SRC_FILES:.f90=.o} \
            $(EXE_FILE).o

# Library and include macros
INCLUDES = -I$(HOME)/local/include
LIBRARIES = -L$(HOME)/local/lib -lnetcdff

# Define common make targets (all, build, clean, install)
include $(CRTM_SOURCE_ROOT)/make.common_targets

# Source link creation and removal
create_links::
	@$(LINK_SCRIPT) $(CRTM_SOURCE_ROOT) $(SRC_FILES) $(INC_FILES)

remove_links::
	@$(UNLINK_SCRIPT) $(SRC_FILES) $(INC_FILES)

# Source dependency lists
include make.dependencies

# Define default rules
include $(CRTM_SOURCE_ROOT)/make.rules
